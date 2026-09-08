<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class MetaConversionsApi
{
    public function sendPurchase(Order $order, ?Request $request = null): bool
    {
        if (!config('services.meta_capi.enabled') || !$order->marketing_consent) {
            return false;
        }

        $pixelId = $this->pixelId();
        $accessToken = trim((string) config('services.meta_capi.access_token'));

        if ($pixelId === '' || $accessToken === '') {
            Log::warning('Meta CAPI is enabled but the pixel ID or access token is missing.');
            return false;
        }

        $order->loadMissing('details.product');
        $purchase = MarketingEvents::purchase($order);

        if (!$purchase) {
            return false;
        }

        $eventId = (string) ($purchase['event_id'] ?? ('order-' . $order->id));
        $cacheKey = 'meta-capi:purchase:' . $eventId;

        // Prevent duplicate server sends from payment success + IPN callbacks.
        // Meta also receives the same event_id from the browser Pixel for deduplication.
        if (!Cache::add($cacheKey, 'sending', now()->addMinutes(2))) {
            return true;
        }

        try {
            $ecommerce = $purchase['ecommerce'];
            $userData = $this->userData($order, $request);

            $event = [
                'event_name' => 'Purchase',
                'event_time' => $order->updated_at?->timestamp ?? time(),
                'event_id' => $eventId,
                'action_source' => 'website',
                'event_source_url' => rtrim((string) config('app.url'), '/') . '/order-success',
                'user_data' => $userData,
                'custom_data' => [
                    'currency' => (string) ($ecommerce['currency'] ?? 'BDT'),
                    'value' => (float) ($ecommerce['value'] ?? 0),
                    'content_type' => 'product',
                    'content_ids' => array_values(array_map(
                        fn (array $item) => (string) $item['item_id'],
                        $ecommerce['items'] ?? []
                    )),
                    'contents' => array_values(array_map(
                        fn (array $item) => [
                            'id' => (string) $item['item_id'],
                            'quantity' => (int) $item['quantity'],
                            'item_price' => (float) $item['price'],
                        ],
                        $ecommerce['items'] ?? []
                    )),
                    'num_items' => array_sum(array_map(
                        fn (array $item) => (int) $item['quantity'],
                        $ecommerce['items'] ?? []
                    )),
                    'order_id' => (string) $order->id,
                ],
            ];

            $payload = ['data' => [$event]];
            $testEventCode = trim((string) config('services.meta_capi.test_event_code'));
            if ($testEventCode !== '') {
                $payload['test_event_code'] = $testEventCode;
            }

            $version = trim((string) config('services.meta_capi.api_version', 'v26.0'));
            if (!preg_match('/^v\d+\.\d+$/', $version)) {
                $version = 'v26.0';
            }

            $response = Http::asJson()
                ->withToken($accessToken)
                ->connectTimeout(2)
                ->timeout((int) config('services.meta_capi.timeout', 5))
                ->post(
                    'https://graph.facebook.com/' . $version . '/' . rawurlencode($pixelId) . '/events',
                    $payload
                );

            if (!$response->successful()) {
                Cache::forget($cacheKey);
                Log::warning('Meta CAPI Purchase send failed.', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => mb_substr($response->body(), 0, 1000),
                ]);
                return false;
            }

            Cache::put($cacheKey, 'sent', now()->addDays(30));
            Log::info('Meta CAPI Purchase sent.', [
                'order_id' => $order->id,
                'event_id' => $eventId,
            ]);

            return true;
        } catch (Throwable $e) {
            Cache::forget($cacheKey);
            Log::warning('Meta CAPI Purchase exception.', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function pixelId(): string
    {
        $configured = trim((string) config('services.meta_capi.pixel_id'));
        if ($configured !== '') {
            return preg_match('/^\d+$/', $configured) ? $configured : '';
        }

        $ids = (string) Setting::query()->whereKey(1)->value('meta_pixel_ids');
        $first = trim(explode(',', $ids)[0] ?? '');

        return preg_match('/^\d+$/', $first) ? $first : '';
    }

    private function userData(Order $order, ?Request $request): array
    {
        $data = [];

        $email = strtolower(trim((string) $order->email));
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['em'] = [hash('sha256', $email)];
        }

        $phone = preg_replace('/\D/', '', (string) $order->phone);
        if (preg_match('/^01[3-9][0-9]{8}$/', $phone)) {
            $phone = '88' . $phone;
        } elseif (str_starts_with($phone, '00880')) {
            $phone = substr($phone, 2);
        }

        if (preg_match('/^8801[3-9][0-9]{8}$/', $phone)) {
            $data['ph'] = [hash('sha256', $phone)];
        }

        if ($order->customer_id) {
            $data['external_id'] = [hash('sha256', (string) $order->customer_id)];
        }

        if ($request) {
            if ($request->ip()) {
                $data['client_ip_address'] = $request->ip();
            }
            if ($request->userAgent()) {
                $data['client_user_agent'] = mb_substr((string) $request->userAgent(), 0, 500);
            }

            $fbp = trim((string) $request->cookie('_fbp'));
            $fbc = trim((string) $request->cookie('_fbc'));
            if ($fbp !== '') {
                $data['fbp'] = $fbp;
            }
            if ($fbc !== '') {
                $data['fbc'] = $fbc;
            }
        }

        return $data;
    }
}
