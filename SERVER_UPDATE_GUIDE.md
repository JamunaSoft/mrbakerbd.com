# বর্তমান production server: Git pull ও Artisan

এই নির্দেশনা mrbakerbd.com-এর বর্তমান panel server-এর জন্য। নতুন VPS setup-এর নির্দেশনা নয়।

- Project: `/var/www/6b6fca4b-6b7c-43e3-b69e-31163c67ecd4/public_html`
- Branch: `main`; remote: `origin`
- PHP 8.3: `/opt/ecp-php83/bin/php`
- সাধারণ `php` কমান্ডে PHP 8.1 চলে; এই প্রজেক্টের Artisan চালাতে নিচের পুরো PHP path ব্যবহার করুন।

## ১. আপডেটের আগে

GitHub-এ নতুন পরিবর্তন push থাকতে হবে। গুরুত্বপূর্ণ deployment-এর আগে hosting panel থেকে files ও database backup নিন।

```bash
cd /var/www/6b6fca4b-6b7c-43e3-b69e-31163c67ecd4/public_html

git status --short
```

Output খালি থাকলে পরের ধাপে যান। `M`, `D` বা `??` থাকলে আগে পরিবর্তন দেখুন:

```bash
git diff --stat
git diff
```

Diff থেকে বের হতে `q` চাপুন। Production-এর পরিবর্তন backup/reconcile করে তারপর pull করুন। `git reset --hard`, `git clean -fd` বা সব ফাইল একসঙ্গে restore করবেন না; প্রয়োজনীয় পরিবর্তন বা uploads হারাতে পারে।

শুধু file permission-এর পার্থক্য কি না দেখুন:

```bash
git diff --summary
```

যদি **শুধু** `old mode/new mode` থাকে এবং panel-এর permissions অপরিবর্তিত রাখতে চান, এই repository-তে mode tracking বন্ধ করতে পারেন:

```bash
git config core.fileMode false
git status --short
```

এটি content-এর পরিবর্তন লুকাবে না এবং ফাইলের permission বদলাবে না।

## ২. সাধারণ Blade/CSS/PHP আপডেট

Working tree পরিষ্কার হলে:

```bash
git pull --ff-only origin main
```

Pull সফল হলে view cache পরিষ্কার করুন:

```bash
/opt/ecp-php83/bin/php artisan view:clear
```

ব্রাউজারে `Ctrl + Shift + R` দিন। Admin products পেজে search, status filter, pagination ও layout পরীক্ষা করুন।

`Already up to date` মানে নতুন commit নেই। এটি production-এর locally modified ফাইল ঠিক করে না। Pull ব্যর্থ হলে আগে error সমাধান করুন।

## ৩. প্রয়োজন অনুযায়ী অন্য Artisan command

প্রতিবার সব command চালানোর প্রয়োজন নেই।

### Route পরিবর্তন হলে

```bash
/opt/ecp-php83/bin/php artisan route:clear
```

### Config বা .env পরিবর্তন হলে

```bash
/opt/ecp-php83/bin/php artisan config:clear
```

### শুধু application cache পরিষ্কার করতে হলে

```bash
/opt/ecp-php83/bin/php artisan cache:clear
```

এটি application-এর cached data মুছে দেয়; শুধু টেবিলের CSS আপডেটের জন্য প্রয়োজন নেই।

### নতুন database migration থাকলে

Migration review এবং database backup নেওয়ার পরই:

```bash
/opt/ecp-php83/bin/php artisan migrate --force
```

Production-এ `migrate:fresh`, `migrate:refresh` বা `db:wipe` চালাবেন না।

### Queue worker চালু থাকলে, worker code পরিবর্তনের পর

```bash
/opt/ecp-php83/bin/php artisan queue:restart
```

Worker আবার চালু রাখার জন্য panel/process manager configured থাকতে হবে।

## ৪. Dependencies বা frontend build পরিবর্তন হলে

`composer.lock` পরিবর্তন হলে PHP 8.3 ব্যবহার করে Composer install করতে হবে। বর্তমান terminal session-এ PHP 8.3 আগে রাখুন:

```bash
export PATH="/opt/ecp-php83/bin:$PATH"
php -v
command -v composer
```

PHP 8.3 এবং Composer পাওয়া গেলে:

```bash
composer install --no-dev --optimize-autoloader
```

Composer পাওয়া না গেলে আগে panel-এর Composer setup/path নির্ধারণ করুন। Production-এ `composer update` দিয়ে dependency version পরিবর্তন করবেন না।

Vite source/dependencies পরিবর্তন হলে, server-এ Node/npm ও প্রয়োজনীয় build setup থাকলে:

```bash
npm ci
npm run build
```

শুধু `resources/views/backend/products/index.blade.php`-এর inline CSS পরিবর্তনে Composer install বা npm build দরকার নেই।

## ৫. সাধারণ error

### Composer requires PHP >= 8.2

`php artisan ...`-এর বদলে ব্যবহার করুন:

```bash
/opt/ecp-php83/bin/php artisan view:clear
```

### Dubious ownership

পরিচিত এই project folder-টির জন্য root terminal-এ একবার:

```bash
git config --global --add safe.directory /var/www/6b6fca4b-6b7c-43e3-b69e-31163c67ecd4/public_html
```

### Local changes would be overwritten / Cannot fast-forward

থামুন এবং output ও `git status --short` দেখুন। Force reset/pull করবেন না; production-এর পরিবর্তন আগে সংরক্ষণ ও reconcile করুন।

### Permission denied / authentication failed

GitHub access বা deploy key/token পরীক্ষা করতে হবে। Token chat বা repository-তে লিখবেন না।

## দ্রুত ব্যবহার: পরিষ্কার working tree ও সাধারণ view আপডেট

প্রথমে `git status --short` দেখে নিশ্চিত হন। তারপর এই block-এ কোনো command ব্যর্থ হলে পরেরটি চলবে না:

```bash
cd /var/www/6b6fca4b-6b7c-43e3-b69e-31163c67ecd4/public_html &&
git pull --ff-only origin main &&
/opt/ecp-php83/bin/php artisan view:clear
```

## ৬. কোন সমস্যায় কোথা থেকে শুরু করবেন

| লক্ষণ | প্রথমে যা করবেন |
| --- | --- |
| কয়েকটি product update-এ Apache 403 | নিচের ModSecurity log পরীক্ষা করুন; cache clear দিয়ে শুরু করবেন না |
| সব admin page-এ 403 | response ও log দেখুন; application-এর `Admin`/`Manager` role যাচাই করুন |
| 419 / Page Expired | edit page নতুন করে খুলুন, প্রয়োজনে আবার login করুন; পুনরাবৃত্তি হলে session/cookie configuration দেখুন |
| 422 বা form-এ validation message | সংশ্লিষ্ট field-এর error অনুযায়ী input ঠিক করুন |
| 500 / Server Error | Laravel ও PHP error log দেখুন |
| 502/503 | hosting panel-এ website/PHP service status এবং Apache/PHP log দেখুন |
| upload ব্যর্থ / 413 | file size, application validation এবং panel-এর website PHP upload limits পরীক্ষা করুন |
| table ডানদিকে সরে যায় | সংশোধিত Blade/CSS production-এ আছে কি না দেখুন |
| Network tab খালি | Filter ঘর খালি, All নির্বাচন, recording চালু ও Keep log টিক দিয়ে আবার Update চাপুন |

Log বা screenshot শেয়ার করার আগে cookie, session, token, password ও customer data বাদ দিন।

## ৭. Product update-এ ModSecurity 403

### এই ঘটনার নিশ্চিত তথ্য — ৬ সেপ্টেম্বর ২০২৬

`/admin/products/59` update-এর `ARGS:description`-এ পুরোনো HTML পাঠানোর সময় চারটি rule trigger হয়েছিল:

| Rule ID | লগে যা পাওয়া গেছে |
| --- | --- |
| `932120` | `Out-Default`-কে PowerShell command হিসেবে শনাক্ত করেছে |
| `941100` | description-এ XSS detection via libinjection |
| `941160` | description-এ HTML injection detection |
| `942320` | `;Open Sans`-কে SQL injection pattern হিসেবে শনাক্ত করেছে |

Score ছিল 20, threshold 5; `949110` rule request-টি block করেছে। Price বদলালেও পুরো description পাঠানো হয় বলে update ব্যর্থ হচ্ছিল।

Server-এ exception যোগ করার পর `apachectl configtest`-এ `Syntax OK` এসেছে এবং `apachectl graceful` fatal error ছাড়া চলেছে। **এরপর product update সফল হয়েছে কি না এখনো ব্যবহারকারী নিশ্চিত করেননি।**

### আবার 403 হলে: terminal commands

প্রথমে browser-এ failed update reproduce করুন। তারপর সংশ্লিষ্ট product ID দিয়ে log filter করুন; নিচের `59` প্রয়োজনমতো বদলান:

```bash
tail -n 500 /var/log/apache2/error.log | grep -F '[uri "/admin/products/59"]' | tail -n 20
```

পুরোনো event খুঁজতে:

```bash
grep -F '[uri "/admin/products/59"]' /var/log/apache2/error.log /var/log/apache2/error.log.1 | tail -n 30
```

Apache log-এ না পেলে audit log-এর শুধু message অংশ দেখুন:

```bash
tail -n 1500 /var/log/modsecurity/audit.log | grep -E 'ModSecurity:|Message:|Apache-Error:'
```

Timestamp, hostname, URI, `[unique_id]`, `[id]`, `[msg]` ও matched field মিলিয়ে একই request-এর event শনাক্ত করুন। অন্য domain বা bot scan-এর rule দেখে product-এর exception পরিবর্তন করবেন না। পুরো audit log-এ cookie ও request body থাকতে পারে।

### বর্তমান exception কোথায় আছে

- Config: `/etc/modsecurity.d/modsec.customisations.conf`
- Apache config: `/etc/apache2/apache2.conf`
- Domain config: `/etc/apache2/vhosts/mrbakerbd.com`
- CRS rules: `/etc/modsecurity.d/owasp/rules/`
- Audit log: `/var/log/modsecurity/audit.log`

এই `/etc/` config **Git repository-র বাইরে**। `git pull` দিয়ে এটি deploy বা backup হয় না। Panel update config regenerate করলে exception এখনও আছে কি না পরীক্ষা করুন।

বর্তমান config-এ block আছে কি না দেখতে:

```bash
grep -n -A 8 -B 2 'id:1001059' /etc/modsecurity.d/modsec.customisations.conf
```

Block আগে থেকেই থাকলে আবার paste করবেন না; duplicate rule ID-তে config test ব্যর্থ হতে পারে। নতুন issue-তে প্রথমে log দেখুন।

### Config বদলানোর নিয়ম

প্রথমে backup — এটি **terminal command**:

```bash
cp -a /etc/modsecurity.d/modsec.customisations.conf "/root/modsec-customisations-$(date +%Y%m%d-%H%M%S).bak"
nano /etc/modsecurity.d/modsec.customisations.conf
```

নিচেরটি **Apache config, terminal command নয়**। এটি nano editor-এ `Include /etc/modsecurity.d/owasp/rules/*.conf` লাইনের আগে একবার থাকবে। অন্য config মুছবেন না:

```apache
# Mr Baker: rich-text product description exclusions
SecRule REQUEST_HEADERS:Host "@rx ^mrbakerbd[.]com(?::443)?$" \
    "id:1001059,phase:1,pass,nolog,t:none,t:lowercase,chain"
    SecRule REQUEST_METHOD "@streq POST" "t:none,chain"
    SecRule REQUEST_URI "@rx ^/admin/products/[0-9]+/?(?:[?].*)?$" \
        "t:none,ctl:ruleRemoveTargetById=932120;ARGS:description,ctl:ruleRemoveTargetById=941100;ARGS:description,ctl:ruleRemoveTargetById=941160;ARGS:description,ctl:ruleRemoveTargetById=942320;ARGS:description"
```

Scope: এই host-এর সব numeric product ID update-এর POST request-এ শুধু `description`-এর জন্য ওই চারটি rule বাদ যায়। এটি product creation (`/admin/products`), অন্য admin page, অন্য field বা `www` host-এর exception নয়। অন্য ক্ষেত্রে সমস্যা হলে আগে সংশ্লিষ্ট log পরীক্ষা করুন।

এই rule নিজে user login যাচাই করে না; Laravel-এর authentication, role ও CSRF checks চালু থাকতে হবে। Rich-text HTML application-এ নিরাপদভাবে sanitize করাও প্রয়োজন; WAF exception HTML sanitization-এর বিকল্প নয়। পুরো ModSecurity, `949110`, অথবা সব XSS/SQL rules বন্ধ করবেন না।

Save: **Ctrl + O → Enter → Ctrl + X**। তারপর **terminal-এ**:

```bash
apachectl configtest
```

শুধু `Syntax OK` এলে:

```bash
apachectl graceful
```

Reload-এ error হলে আগে সেটি সমাধান করুন। Browser থেকে আগে ব্যর্থ হওয়া product update করুন, সংরক্ষিত value যাচাই করুন এবং নতুন log দেখুন। অন্য একটি product update-ও পরীক্ষা করুন। **ModSecurity config পরিবর্তনে Artisan cache clear লাগে না।**

### Config test ব্যর্থ হলে rollback

Reload করবেন না। Backup list দেখুন:

```bash
ls -lt /root/modsec-customisations-*.bak
```

এই session-এ পরিবর্তনের আগে নেওয়া backup-এর সঠিক নাম বেছে restore করুন। নিচের placeholder বদলে তারপর চালাবেন:

```bash
cp -a /root/REPLACE_WITH_BACKUP_FILENAME.bak /etc/modsecurity.d/modsec.customisations.conf
apachectl configtest
```

`Syntax OK` এলে প্রয়োজনমতো `apachectl graceful` চালান। Backup না চিনে বা অন্যের নতুন config না দেখে restore করবেন না।

পদ্ধতির reference: [ModSecurity v2 Actions](https://github.com/owasp-modsecurity/ModSecurity/wiki/Reference-Manual-%28v2.x%29-Actions)।

## ৮. Apache warnings

এই server-এ আগে থেকেই দেখা গেছে:

- `DocumentRoot .../phpmyadmin.mrbakerbd.com.mrbakerbd.com does not exist`
- `Could not reliably determine the server's fully qualified domain name`

এগুলো `Syntax OK`-এর সঙ্গে দেখা গেছে এবং product description-এর ModSecurity block-এর কারণ নয়। Panel-এর domain settings থেকে ভুল/অব্যবহৃত phpMyAdmin virtual host যাচাই করুন; warning দূর করতে আন্দাজে directory তৈরি বা vhost delete করবেন না। Global `ServerName`-ও panel-এর ব্যবস্থাপনা অনুযায়ী ঠিক করতে হবে। পরিবর্তনের পর config test করুন।

## ৯. DataTable layout বা পুরোনো CSS

এই ঘটনার কারণ ছিল theme-এর `.dataTables_length`-এ `float: left; width: 50%`; এর ফলে টেবিল পাশে সরে যাচ্ছিল। Fix হলো product page-এ scoped CSS দিয়ে float সরানো এবং table scroll container-এ `clear: both` রাখা।

Production-এ fix আছে কি না দেখুন:

```bash
git fetch origin
git diff origin/main -- resources/views/backend/products/index.blade.php
```

Diff থাকলে আগে review করুন। **শুধু নিশ্চিত হলে যে local ফাইল পুরোনো এবং GitHub-এর version-টাই প্রয়োজন**, এই ফাইলের backup নিয়ে restore করুন:

```bash
cp resources/views/backend/products/index.blade.php "/root/products-index-$(date +%Y%m%d-%H%M%S).blade.php"
git restore --source=origin/main -- resources/views/backend/products/index.blade.php
/opt/ecp-php83/bin/php artisan view:clear
```

তারপর `Ctrl + Shift + R`। Restore ওই ফাইলের local পরিবর্তন replace করে; সাধারণ deployment-এর সময় অভ্যাস করে এই command চালাবেন না।

## ১০. Laravel/PHP error বা image upload সমস্যা

Project folder থেকে log দেখুন:

```bash
tail -n 80 storage/logs/laravel.log
tail -n 80 /var/www/6b6fca4b-6b7c-43e3-b69e-31163c67ecd4/php-error.log
```

Log filename বদলে গেলে `ls -lt storage/logs` দিয়ে দেখুন। Error-এর সময় ও request মেলান। Production-এ `APP_DEBUG=true` করে error প্রকাশ করবেন না।

Upload সমস্যায় browser-এর validation message আগে পড়ুন। `413` হলে Apache/proxy body limit এবং panel-এ website-এর PHP `upload_max_filesize`/`post_max_size` দেখুন। CLI `php -i`-এর settings website-এর PHP-FPM settings-এর সমান নাও হতে পারে।

Permission error হলে বর্তমান ownership ও disk space দেখুন:

```bash
ls -ld storage bootstrap/cache public/images public/images/products
df -h .
```

Panel-এর website user অনুযায়ী প্রয়োজনীয় directory permission ঠিক করুন; `chmod -R 777` বা পুরো project-এর ownership আন্দাজে বদলাবেন না।
