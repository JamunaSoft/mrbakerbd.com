<?php

namespace Tests\Feature;

use App\Http\Middleware\SiteSettings;
use App\Http\Middleware\UpdateLastOnline;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CartMarketingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array', 'cache.default' => 'array']);
        DB::purge('sqlite');
        $this->withoutMiddleware([SiteSettings::class, UpdateLastOnline::class, \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug'); $table->integer('type');
            $table->integer('category_id')->nullable(); $table->decimal('regular_price'); $table->decimal('special_price')->nullable();
        });
        DB::table('products')->insert(['id' => 7, 'name' => 'Cake', 'slug' => 'cake', 'type' => 1, 'regular_price' => 300]);
    }

    private function cart(): array
    {
        return [7 => ['name' => 'Cake', 'slug' => 'cake', 'price' => 300, 'quantity' => 2, 'image' => 'cake.jpg']];
    }

    public function test_successful_add_emits_only_added_quantity_and_invalid_price_emits_nothing(): void
    {
        $payload = ['id' => 7, 'name' => 'Cake', 'slug' => 'cake', 'price' => 300, 'quantity' => 1, 'image' => 'cake.jpg'];
        $this->withSession(['cart' => $this->cart()])->post('/add-to-cart', $payload)
            ->assertRedirect(route('cart'))
            ->assertSessionHas('cart.7.quantity', 3)
            ->assertSessionHas('marketing_events.0.event', 'add_to_cart')
            ->assertSessionHas('marketing_events.0.ecommerce.items.0.quantity', 1);
        session()->forget('marketing_events');
        $payload['price'] = 1;
        $this->post('/add-to-cart', $payload)->assertSessionHasErrors('price')->assertSessionMissing('marketing_events');
    }

    public function test_quantity_decrease_and_remove_emit_actual_deltas(): void
    {
        $this->withSession(['cart' => $this->cart()])->post('/cart/update/7', ['quantity' => 1])
            ->assertSessionHas('marketing_events.0.event', 'remove_from_cart')
            ->assertSessionHas('marketing_events.0.ecommerce.items.0.quantity', 1)
            ->assertSessionHas('marketing_events.0.ecommerce.value', 300);
        $this->post('/cart/remove/7')->assertSessionHas('cart', [])
            ->assertSessionHas('marketing_events.0.event', 'remove_from_cart');
    }
}
