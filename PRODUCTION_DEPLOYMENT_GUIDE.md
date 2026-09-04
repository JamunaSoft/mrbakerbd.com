# Mr. Baker Production Deployment Guide

This guide deploys the Laravel application on a blank Ubuntu 24.04 LTS VPS using:

- Nginx
- PHP 8.3 FPM
- MySQL 8
- Node.js for Vite production builds
- Supervisor for Laravel queue workers
- Cron for Laravel scheduled tasks
- Certbot for HTTPS

Replace every value inside `<ANGLE_BRACKETS>` before running a command. Do not put passwords, private keys, or real API secrets in Git.

## 1. Before Starting

Required:

- VPS public IP: `<VPS_IP>`
- SSH user: `<SSH_USER>`
- Domain DNS `A` records for `mrbakerbd.com` and `www.mrbakerbd.com` pointing to `<VPS_IP>`
- A database backup if existing products, customers, orders, and settings must be preserved
- Production credentials for MySQL, SMTP, SSLCommerz, Sentry, GTM, and GA4 as applicable

The repository requires PHP `8.2+`, MySQL `8.0+`, Composer, and Node.js. The application must be served from Laravel's `public` directory, not the repository root.

## 2. Connect and Update Ubuntu

```bash
ssh <SSH_USER>@<VPS_IP>

sudo apt update && sudo apt full-upgrade -y
sudo timedatectl set-timezone Asia/Dhaka
```

Create a non-root deployment user if logging in as root:

```bash
sudo adduser deploy
sudo usermod -aG sudo deploy
sudo mkdir -p /home/deploy/.ssh
sudo cp ~/.ssh/authorized_keys /home/deploy/.ssh/authorized_keys
sudo chown -R deploy:deploy /home/deploy/.ssh
sudo chmod 700 /home/deploy/.ssh
sudo chmod 600 /home/deploy/.ssh/authorized_keys
```

After verifying a second SSH session works as `deploy`, disable root/password SSH login in `/etc/ssh/sshd_config`:

```text
PermitRootLogin no
PasswordAuthentication no
```

```bash
sudo systemctl restart ssh
```

## 3. Install Server Packages

```bash
sudo apt install -y nginx mysql-server unzip git curl \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-curl php8.3-mbstring \
  php8.3-xml php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl \
  php8.3-opcache supervisor certbot python3-certbot-nginx
```

Install Composer for the deployment user:

```bash
cd /tmp
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
composer --version
```

Install Node.js 22 LTS for building assets:

```bash
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs
node --version
npm --version
```

## 4. Configure MySQL

Run the hardening wizard and answer the prompts appropriately:

```bash
sudo mysql_secure_installation
```

Create a dedicated database user. Do not use MySQL `root` from Laravel:

```bash
sudo mysql
```

```sql
CREATE DATABASE `mrbakerbd` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER '<DB_USER>'@'localhost' IDENTIFIED BY '<STRONG_DB_PASSWORD>';
GRANT ALL PRIVILEGES ON `mrbakerbd`.* TO '<DB_USER>'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

If preserving the current local data, upload and import a SQL backup:

```bash
scp database-backup.sql deploy@<VPS_IP>:/tmp/database-backup.sql
mysql -u <DB_USER> -p mrbakerbd < /tmp/database-backup.sql
```

Do not run `migrate:fresh` or `db:wipe` on production.

## 5. Upload the Application

Recommended location:

```bash
sudo mkdir -p /var/www/mrbakerbd.com
sudo chown -R deploy:www-data /var/www/mrbakerbd.com
sudo chmod 2775 /var/www/mrbakerbd.com
```

Clone as the deployment user:

```bash
sudo -u deploy git clone https://github.com/JamunaSoft/mrbakerbd.com.git /var/www/mrbakerbd.com
cd /var/www/mrbakerbd.com
```

If the repository is private, configure a read-only deploy key or GitHub token. Never commit `.env`.

## 6. Install Dependencies and Configure `.env`

```bash
cd /var/www/mrbakerbd.com
sudo -u deploy composer install --no-dev --optimize-autoloader
sudo -u deploy npm ci
sudo -u deploy npm run build
sudo -u deploy touch .env
sudo -u deploy php artisan key:generate --force
```

Edit `.env`:

```dotenv
APP_NAME="Mr. Baker"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mrbakerbd.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mrbakerbd
DB_USERNAME=<DB_USER>
DB_PASSWORD=<STRONG_DB_PASSWORD>

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=<SMTP_HOST>
MAIL_PORT=587
MAIL_USERNAME=<SMTP_USERNAME>
MAIL_PASSWORD=<SMTP_PASSWORD>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=<MAIL_FROM_ADDRESS>
MAIL_FROM_NAME="${APP_NAME}"

# Use GTM only, or direct GA4 only. If GTM is used, configure GA4 inside GTM.
GOOGLE_TAG_MANAGER_ID=<GTM-ID>
# GOOGLE_ANALYTICS_ID=<GA4-MEASUREMENT-ID>
```

The actual project `.env` may contain payment, mail, Sentry, or other credentials. Copy those values securely, but rotate any credential that has been exposed or shared outside the server.

This repository intentionally does not track `.env.example`; create `.env` directly on the VPS and keep it out of Git.

## 7. Run Laravel Production Setup

```bash
cd /var/www/mrbakerbd.com
sudo -u deploy php artisan migrate --force
sudo -u deploy php artisan storage:link
sudo -u deploy php artisan optimize:clear
sudo -u deploy php artisan optimize
```

If using the public filesystem, confirm uploaded files are available through `public/storage`:

```bash
ls -la public/storage
```

Set Laravel writable directories:

```bash
sudo chown -R deploy:www-data storage bootstrap/cache
sudo find storage bootstrap/cache -type d -exec chmod 2775 {} \;
sudo find storage bootstrap/cache -type f -exec chmod 664 {} \;
```

## 8. Configure PHP-FPM

Edit `/etc/php/8.3/fpm/php.ini` and set production-safe limits suitable for product images:

```text
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 120
opcache.enable = 1
opcache.validate_timestamps = 0
```

Restart PHP-FPM:

```bash
sudo systemctl restart php8.3-fpm
sudo systemctl enable php8.3-fpm nginx mysql supervisor
```

## 9. Configure Nginx

Create `/etc/nginx/sites-available/mrbakerbd.com`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name mrbakerbd.com www.mrbakerbd.com;
    root /var/www/mrbakerbd.com/public;

    index index.php index.html;
    charset utf-8;
    client_max_body_size 25M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }

    access_log /var/log/nginx/mrbaker_access.log;
    error_log /var/log/nginx/mrbaker_error.log;
}
```

Enable the site and test the configuration:

```bash
sudo ln -s /etc/nginx/sites-available/mrbakerbd.com /etc/nginx/sites-enabled/mrbakerbd.com
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

## 10. Enable HTTPS

Run this only after DNS points to the VPS and HTTP is working:

```bash
sudo certbot --nginx -d mrbakerbd.com -d www.mrbakerbd.com
sudo certbot renew --dry-run
```

Update `.env` to use `https://mrbakerbd.com`, then refresh cached configuration:

```bash
cd /var/www/mrbakerbd.com
sudo -u deploy php artisan optimize:clear
sudo -u deploy php artisan optimize
```

## 11. Queue Worker and Scheduler

Create `/etc/supervisor/conf.d/mrbaker-worker.conf`:

```ini
[program:mrbaker-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/mrbakerbd.com/artisan queue:work --sleep=3 --tries=3 --timeout=90
directory=/var/www/mrbakerbd.com
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=deploy
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/mrbakerbd.com/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status
```

Add Laravel's scheduler for the `deploy` user:

```bash
sudo -u deploy crontab -e
```

```cron
* * * * * cd /var/www/mrbakerbd.com && php artisan schedule:run >> /dev/null 2>&1
```

## 12. Firewall

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw --force enable
sudo ufw status
```

Do not expose MySQL port `3306` publicly unless there is a specific, secured requirement.

## 13. First Deployment Verification

```bash
cd /var/www/mrbakerbd.com
sudo -u deploy php artisan about
sudo -u deploy php artisan migrate:status
sudo -u deploy php artisan route:list
curl -I https://mrbakerbd.com
curl -I https://mrbakerbd.com/robots.txt
curl -I https://mrbakerbd.com/sitemap.xml
sudo systemctl status nginx php8.3-fpm mysql supervisor --no-pager
```

Check these manually:

- Homepage and category/product images load
- Admin login works
- Product image upload creates the optimized WebP files
- Checkout validation and payment callbacks use the HTTPS domain
- Confirmation emails are delivered
- Queue worker has no errors
- `/robots.txt` and `/sitemap.xml` return successfully
- Browser shows a valid HTTPS certificate

## 14. Updating the Application

```bash
cd /var/www/mrbakerbd.com
sudo -u deploy git pull --ff-only origin main
sudo -u deploy composer install --no-dev --optimize-autoloader
sudo -u deploy npm ci
sudo -u deploy npm run build
sudo -u deploy php artisan migrate --force
sudo -u deploy php artisan optimize:clear
sudo -u deploy php artisan optimize
sudo supervisorctl restart mrbaker-worker:*
sudo systemctl reload php8.3-fpm
```

For zero-downtime releases, use a release directory strategy instead of updating the live directory in place.

## 15. Backups

Create a private backup directory outside the web root:

```bash
sudo mkdir -p /var/backups/mrbaker
sudo mysqldump --single-transaction --routines --triggers \
  -u <DB_USER> -p mrbakerbd | gzip | sudo tee \
  /var/backups/mrbaker/mrbaker-$(date +%F).sql.gz > /dev/null
```

Back up the uploaded files as well. Store database and file backups on a separate server or object storage, not only on the VPS.

## 16. Troubleshooting

View Laravel logs:

```bash
sudo tail -f /var/www/mrbakerbd.com/storage/logs/laravel.log
```

View web-server logs:

```bash
sudo tail -f /var/log/nginx/mrbaker_error.log
sudo journalctl -u php8.3-fpm -n 100 --no-pager
```

Common fixes:

```bash
sudo chown -R deploy:www-data storage bootstrap/cache
sudo -u deploy php artisan optimize:clear
sudo nginx -t && sudo systemctl reload nginx
sudo supervisorctl restart mrbaker-worker:*
```

Never enable `APP_DEBUG=true` on the public production site.
