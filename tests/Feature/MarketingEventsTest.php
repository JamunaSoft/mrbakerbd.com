<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Services\MarketingEvents;
use Tests\TestCase;

class MarketingEventsTest extends TestCase
{
    private function order(int $method, int $paid): Order
    {
        $order = new Order;
        $order->forceFill(['id' => 42, 'payment_method' => $method, 'payment_status' => $paid, 'status' => 'Pending', 'shipping_charge' => 100, 'discount' => 20]);
        $detail = new OrderDetail(['product_id' => 7, 'qty' => 2, 'price' => 300]);
        $detail->setRelation('product', new Product(['name' => 'Chocolate Cake']));
        $order->setRelation('details', collect([$detail]));
        return $order;
    }

    public function test_cod_and_verified_online_orders_have_consistent_purchase_values(): void
    {
        foreach ([[1, 0], [2, 1]] as [$method, $paid]) {
            $event = MarketingEvents::purchase($this->order($method, $paid));
            $this->assertSame('42', $event['ecommerce']['transaction_id']);
            $this->assertSame('order-42', $event['event_id']);
            $this->assertEquals(580, $event['ecommerce']['value']);
            $this->assertEquals(100, $event['ecommerce']['shipping']);
            $this->assertEquals(290, $event['ecommerce']['items'][0]['price']);
            $this->assertSame('BDT', $event['ecommerce']['currency']);
            $this->assertArrayNotHasKey('email', $event);
        }
    }

    public function test_unpaid_online_and_cancelled_orders_do_not_convert(): void
    {
        $this->assertNull(MarketingEvents::purchase($this->order(2, 0)));
        $order = $this->order(1, 0);
        $order->status = 'Cancelled';
        $this->assertNull(MarketingEvents::purchase($order));
    }

    public function test_enhanced_data_normalizes_and_hashes_contact_details(): void
    {
        $order = $this->order(1, 0);
        $order->email = ' Test.User@Gmail.com ';
        $order->phone = '01712-345678';
        $data = MarketingEvents::enhancedConversions($order);
        $this->assertSame(hash('sha256', 'testuser@gmail.com'), $data['sha256_email_address']);
        $this->assertSame(hash('sha256', '+8801712345678'), $data['sha256_phone_number']);
        $order->email = 'invalid';
        $order->phone = '000';
        $this->assertSame([], MarketingEvents::enhancedConversions($order));
    }

    public function test_cart_events_preserve_product_ids_variants_and_quantity(): void
    {
        $items = MarketingEvents::cartItems(['7-12' => ['name' => 'Cake', 'variant_id' => 12, 'price' => '350.50', 'quantity' => 3, 'custom_note' => 'Private birthday message']]);
        $event = MarketingEvents::event('add_to_cart', $items);
        $this->assertSame('7', $items[0]['item_id']);
        $this->assertSame('12', $items[0]['item_variant']);
        $this->assertEquals(1051.5, $event['ecommerce']['value']);
        $this->assertStringNotContainsString('Private birthday message', json_encode($event));
    }
}
