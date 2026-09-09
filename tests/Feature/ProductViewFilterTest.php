<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\ProductViewService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductViewFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('views')->default(0);
            $table->timestamps();
        });
        (require database_path('migrations/2026_09_09_000000_create_product_views_table.php'))->up();
        $this->travelTo(now()->setDate(2026, 9, 9)->setTime(12, 0));
    }

    public function test_periods_count_only_views_within_calendar_boundaries(): void
    {
        DB::table('products')->insert([
            ['id' => 1, 'name' => 'Cake A', 'views' => 100],
            ['id' => 2, 'name' => 'Cake B', 'views' => 200],
        ]);
        foreach (['2025-12-31 23:59:59', '2026-01-01 00:00:00', '2026-09-01 00:00:00', '2026-09-07 00:00:00'] as $date) {
            DB::table('product_views')->insert(['product_id' => 1, 'viewed_at' => $date]);
        }
        DB::table('product_views')->insert([
            ['product_id' => 2, 'viewed_at' => '2026-09-06 23:59:59'],
            ['product_id' => 2, 'viewed_at' => '2026-09-10 00:00:00'],
        ]);
        $service = new ProductViewService;
        foreach (['week' => 1, 'month' => 2, 'year' => 3] as $period => $expected) {
            $results = $service->mostViewed($period);
            $this->assertEquals(1, $results->first()->id);
            $this->assertEquals($expected, $results->first()->views);
        }
        $this->assertCount(1, $service->mostViewed('week'));
        $this->assertEquals(200, $service->mostViewed('all')->first()->views);
        $this->assertEquals(2, $service->mostViewed('all')->first()->id);
    }

    public function test_recording_updates_lifetime_and_dated_views(): void
    {
        DB::table('products')->insert(['id' => 1, 'name' => 'Cake', 'views' => 20]);
        Product::withoutEvents(function () {
            $service = new ProductViewService;
            $product = Product::findOrFail(1);
            $service->record($product);
            $service->record($product);
            $this->assertEquals(22, $product->fresh()->views);
            $this->assertEquals(2, $service->mostViewed('week')->first()->views);
        });
    }
}
