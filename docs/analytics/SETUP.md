# Analytics and advertising setup

All account IDs are managed in **Admin → System → Settings → Analytics & Advertising**. There are no default GA4, Google Ads or Meta accounts. Blank IDs disable that destination. GTM also uses the saved Settings value, not an environment fallback.

## Activate with website settings

1. Enter your new GA4 Measurement ID (`G-…`).
2. Enter the Google Ads conversion ID (`AW-…`) and conversion label together. Use the purchase conversion action's label.
3. Enter your new Meta Pixel ID(s), separated by commas.
4. Choose **Website** and save. This sends events directly after the visitor accepts optional cookies. GTM is not required for this mode. If you also enter a GTM container, do not publish separate tags for the same destinations/events.
5. Enable Enhanced Conversions in the Google Ads account and accept its customer data terms, then enable the checkbox in Settings.

## Activate through GTM

1. Create/use a web container and save its `GTM-…` ID in Settings.
2. Download **GTM setup** from Settings (`public/tracking/gtm-container.json`). In GTM → Admin → Import Container, select a workspace and **Merge**, reviewing the changes.
3. The import adds one Custom HTML tag on All Pages. It calls the website's marketing adapter, which loads Google and Meta tags using the current Settings IDs. Account IDs are intentionally absent from the export. Do not add separate GA4/Ads/Meta tags for these same events.
4. Preview the container, select **Google Tag Manager** delivery in Settings, accept optional cookies and verify the tag and events. Publish the container after verification. If the imported tag is not published, GTM mode will not send to the configured destinations.
5. To return to direct delivery, choose **Website**. The imported tag checks delivery mode, so it does not activate a second copy.

The GTM export is generated and syntax-checked locally. Import acceptance and account-side reporting need verification in your account. No Google or Meta account changes have been published by this code change.

## Events and values

| Website event | GA4 | Meta |
| --- | --- | --- |
| Page load | `page_view` | `PageView` |
| Shop/category/search results | `view_item_list` | — |
| Product page | `view_item` | `ViewContent` |
| Successful cart addition / quantity increase | `add_to_cart` | `AddToCart` |
| Cart removal / clear / quantity decrease | `remove_from_cart` | — |
| Cart page | `view_cart` | — |
| Nonempty checkout page | `begin_checkout` | `InitiateCheckout` |
| Eligible order confirmation | `purchase` | `Purchase` |

Google Ads sends its purchase conversion on the same eligible confirmation. GA4 uses explicit destination routing. All ecommerce values use BDT, product IDs, unit prices and quantities. Purchase value excludes shipping/tax and includes order discounts; shipping is a separate GA4 parameter. Ads and Meta use the same merchandise value. Order-level discounts are allocated across item prices.

Cash on Delivery counts at order placement. Online orders count only after verified payment. Cancelled/failed and unpaid online orders do not generate purchases. Purchases are built from stored order details on the signed confirmation route, not browser-submitted totals. A cancelled COD order later on does not automatically retract the previously sent conversion.

Order ID is the GA4/Ads transaction ID and `order-{id}` is Meta's event ID. Per-destination localStorage markers suppress repeat purchase dispatches in the same browser for 90 days, including refreshes. Browser storage clearing, another browser/device, ad blockers or interrupted requests can still affect delivery. These are browser events, not server-side Meta CAPI or offline conversion uploads; payment IPNs without a returning browser cannot emit browser purchases.

## Consent and customer data

The optional-cookie banner defaults tracking to denied. Accepting starts GTM/Google/Meta. Rejecting does not load them. Cookie preferences can be reopened and consent withdrawn; withdrawal reloads the page to unload existing tags. The choice lasts 180 days. Google Consent Mode supplies all four analytics/ads consent fields. A previously accepted consent cookie also permits GTM's noscript fallback.

Enhanced Conversions normalizes and SHA-256 hashes email and phone on the server, then supplies them only around the Ads conversion command when consent and the Settings checkbox permit it. No raw customer email, phone, address or cake message is included in ecommerce data. Hashed contacts are not added to GA4 ecommerce events. Google's page URL/referrer parameters omit query strings (including signed-link signatures). Meta and externally configured GTM tags have their own URL collection behavior; avoid putting personal contact data in site URLs.

## Verify before relying on reports

- Use a new browser profile: reject cookies and confirm no Google/Meta tracking loads; accept and confirm each configured destination initializes once.
- GTM: Preview → inspect the imported Custom HTML tag and ecommerce events. No duplicate destination tags should be enabled.
- GA4: use Tag Assistant/DebugView and Realtime; check item ID, quantity, BDT value and transaction ID. Disable unwanted automatic form tracking/user-provided-data collection in the GA4 account if enabled there.
- Google Ads: Tag Assistant should show exactly one conversion for the correct ID/label; inspect Enhanced Conversions diagnostics after processing.
- Meta: Test Events / Pixel Helper should show one PageView and Purchase per configured pixel.
- Place a test COD order and a sandbox paid online order; retry/refresh the confirmation and check duplicate suppression. Do not count a failed online payment.
- Clear an ID and confirm that destination stops on the next page load; change an ID and verify events go to the new account.

## Deployment and local validation

Deploy the changed PHP/Blade files, migrations, `public/js/marketing.js` and `public/tracking/gtm-container.json`, then run `php artisan migrate --force`, `php artisan view:clear` and clear `settings` / `settings_data` cache keys. Existing saved account settings should be reviewed before activation.

Tests:

```sh
php artisan test tests/Feature/TagManagerSettingsTest.php tests/Feature/MarketingEventsTest.php tests/Feature/MarketingSettingsPersistenceTest.php tests/Feature/AdminEntryRedirectTest.php tests/Feature/CartMarketingTest.php
node --test tests/JavaScript/marketing.test.mjs
```

The persistence test explicitly uses an isolated in-memory SQLite connection; PHP PDO SQLite is required.

References: [Google ecommerce events](https://developers.google.com/tag-platform/gtagjs/reference/events), [Enhanced Conversions with the Google tag](https://support.google.com/google-ads/answer/13258081?hl=en), [Consent Mode](https://developers.google.com/tag-platform/security/guides/consent).
