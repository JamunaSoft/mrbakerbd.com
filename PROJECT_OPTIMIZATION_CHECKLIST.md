# Mr. Baker Website Optimization Checklist

## Completed

- [x] Product, category, shop and CMS page SEO titles and descriptions
- [x] Canonical URLs, Open Graph and Twitter card metadata
- [x] Product Schema.org structured data
- [x] Dynamic sitemap.xml
- [x] Updated robots.txt
- [x] Product image WebP delivery with original-image fallback
- [x] Automatic WebP generation for new admin uploads
- [x] Lazy loading for frontend product images
- [x] Missing local slider and category assets restored
- [x] Admin order list changed to server-side pagination
- [x] Admin dashboard summary counts cached for 30 seconds
- [x] Dashboard processing orders limited and eager-loaded
- [x] Admin customer, user, product, category, page, slide, contact and feedback lists paginated
- [x] Admin list queries restricted to required columns
- [x] Admin users, products and categories relationships eager-loaded
- [x] Category repository no longer loads every product for admin dropdowns
- [x] Null online timestamps display as Never instead of 0 seconds ago
- [x] Authenticated user activity updates last_online at most once per minute
- [x] Dashboard separates payment country from Bangladesh delivery location
- [x] Dashboard shows order source, country, division, district and area statistics
- [x] Payment country is detected server-side from customer IP
- [x] Google Tag Manager / GA4 integration scaffold added

## Before Production Deploy

1. Set APP_ENV=production and APP_DEBUG=false.
2. Set APP_URL=https://mrbakerbd.com.
3. Run php artisan optimize.
4. Run php artisan storage:link if storage-backed assets are used.
5. Confirm the web server points to the public directory.
6. Confirm https://mrbakerbd.com/robots.txt and /sitemap.xml return 200.
7. Submit /sitemap.xml in Google Search Console.
8. Clear browser and CDN cache after replacing images or CSS.
9. Add GOOGLE_TAG_MANAGER_ID or GOOGLE_ANALYTICS_ID to the production environment.
10. If using GTM, configure the GA4 tag inside GTM instead of loading GA4 directly as well.

## Ongoing Admin Performance

- Keep order tables paginated; do not replace paginate() with get() for large lists.
- Use select() to load only fields shown in a table.
- Use eager loading when a view loops over relationships.
- Keep dashboard widgets limited to recent/top records.
- Add database indexes for frequently filtered columns: orders.status, orders.created_at, products.status, products.featured, and products.slug.
- Avoid putting large HTML descriptions into list queries.
- Periodically remove unused/orphaned uploaded image files.

## Verification Commands

    php artisan optimize:clear
    php artisan optimize
    php artisan route:list
    php artisan view:clear
    php artisan test

The current test environment previously lacked the SQLite PDO driver, so test failures caused by that environment should be resolved before treating the full suite as green.
