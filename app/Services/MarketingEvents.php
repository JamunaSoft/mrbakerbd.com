<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;

class MarketingEvents
{
    public static function cartItems(array $cart): array
    {
        $items = [];
        foreach ($cart as $key => $item) {
            $items[] = [
                'item_id' => (string) explode('-', (string) $key)[0],
                'item_name' => (string) $item['name'],
                'item_variant' => (string) ($item['variant_id'] ?? ''),
                'price' => round((float) $item['price'], 2),
                'quantity' => (int) $item['quantity'],
            ];
        }
        return $items;
    }

    public static function productItem(Product $product): array
    {
        $variant = (int) $product->type === 2 ? $product->details->first() : null;
        return [
            'item_id' => (string) $product->id,
            'item_name' => $product->name,
            'item_variant' => (string) ($variant?->id ?? ''),
            'price' => round((float) ($variant ? ($variant->special_price ?? $variant->regular_price) : ($product->special_price ?? $product->regular_price)), 2),
            'quantity' => 1,
        ];
    }

    public static function event(string $name, array $items, array $extra = []): array
    {
        return ['event' => $name, 'ecommerce' => array_merge([
            'currency' => 'BDT',
            'value' => round(array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $items)), 2),
            'items' => $items,
        ], $extra)];
    }

    public static function purchase(Order $order): ?array
    {
        // COD is a placed order; online orders count only after payment verification.
        if (in_array(strtolower((string) $order->status), ['cancelled', 'canceled', 'failed']) ||
            ((int) $order->payment_method !== 1 && (int) $order->payment_status !== 1)) {
            return null;
        }
        $items = $order->details->map(fn ($detail) => [
            'item_id' => (string) $detail->product_id,
            'item_name' => (string) ($detail->product?->name ?? 'Product'),
            'item_variant' => trim(($detail->size ?? '').' '.($detail->label ?? '')),
            'price' => round((float) $detail->price, 2),
            'quantity' => (int) $detail->qty,
        ])->all();
        // Allocate any order discount to unit prices so value agrees with the items.
        $subtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $items));
        $discount = min($subtotal, max(0, (float) $order->discount));
        if ($subtotal > 0 && $discount > 0) {
            foreach ($items as &$item) {
                $item['discount'] = round($item['price'] * $discount / $subtotal, 6);
                $item['price'] -= $item['discount'];
            }
            unset($item);
        }
        return self::event('purchase', $items, [
            'transaction_id' => (string) $order->id,
            'shipping' => round((float) $order->shipping_charge, 2),
            'tax' => 0,
        ]) + ['event_id' => 'order-'.$order->id];
    }

    public static function enhancedConversions(Order $order): array
    {
        $data = [];
        $email = strtolower(trim((string) $order->email));
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            [$local, $domain] = explode('@', $email);
            if (in_array($domain, ['gmail.com', 'googlemail.com'])) {
                $local = str_replace('.', '', $local);
            }
            $data['sha256_email_address'] = hash('sha256', $local.'@'.$domain);
        }
        $rawPhone = trim((string) $order->phone);
        $phone = preg_replace('/\D/', '', $rawPhone);
        $international = str_starts_with($rawPhone, '+') || str_starts_with($phone, '00') || str_starts_with($phone, '880');
        if (preg_match('/^01[3-9][0-9]{8}$/', $phone)) {
            $phone = '88'.$phone;
            $international = true;
        } elseif (str_starts_with($phone, '00')) {
            $phone = substr($phone, 2);
        }
        if ($international && preg_match('/^[1-9][0-9]{7,14}$/', $phone)) {
            $data['sha256_phone_number'] = hash('sha256', '+'.$phone);
        }
        return $data;
    }
}
