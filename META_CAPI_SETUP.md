# Meta Conversions API (CAPI) setup

The application sends verified **Purchase** events to Meta from the Laravel server and uses the same event ID as the browser Pixel (`order-{order_id}`) so Meta can deduplicate browser + server events.

CAPI is intentionally **off by default** and only sends for orders where the customer accepted the site's optional analytics/advertising cookies.

## 1. Generate the Meta access token

In Meta Events Manager:

1. Open **Mr Baker Website Events**.
2. Go to **Settings**.
3. Find **Conversions API**.
4. Generate an access token for the dataset/pixel.
5. Keep the token secret. Do not commit it to Git or paste it into frontend/admin fields.

## 2. Production environment variables

Add these to the production `.env`:

```env
META_CAPI_ENABLED=true
META_CAPI_PIXEL_ID=4355897001293794
META_CAPI_ACCESS_TOKEN=PASTE_THE_META_ACCESS_TOKEN_HERE
META_CAPI_API_VERSION=v26.0
META_CAPI_TIMEOUT=5
```

`META_CAPI_PIXEL_ID` is optional because the service can fall back to the first Pixel ID configured in Admin → Settings → Meta Pixel IDs.

## 3. Run deployment commands

```bash
php artisan migrate --force
php artisan optimize:clear
```

## 4. Test with Meta Test Events

In Events Manager → **Test events**, copy the server test event code and temporarily add:

```env
META_CAPI_TEST_EVENT_CODE=TEST12345
```

Then run:

```bash
php artisan config:clear
```

Place a successful test order after accepting optional cookies. In Test Events you should see a **Purchase** server event. If the browser Pixel also fires Purchase, Meta should show browser/server deduplication because both use the same event ID.

After the test succeeds, remove `META_CAPI_TEST_EVENT_CODE` from `.env` and run:

```bash
php artisan config:clear
```

## What is sent

For Purchase events the server sends:

- event name and event time
- event ID (`order-{id}`)
- action source = website
- SHA-256 hashed email and phone when valid
- customer ID hash when available
- browser IP/user agent and `_fbp`/`_fbc` when the purchase confirmation request has them
- BDT value, product IDs, quantities, item prices and order ID

The Meta access token is never exposed to browser JavaScript and is not logged.
