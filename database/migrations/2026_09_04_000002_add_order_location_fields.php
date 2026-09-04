<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'payment_country' => ['length' => 100, 'after' => 'email'],
            'delivery_country' => ['length' => 100, 'after' => 'payment_country'],
            'division' => ['length' => 100, 'after' => 'delivery_country'],
            'district' => ['length' => 100, 'after' => 'division'],
            'area' => ['length' => 150, 'after' => 'district'],
        ] as $column => $definition) {
            if (!Schema::hasColumn('orders', $column)) {
                Schema::table('orders', function (Blueprint $table) use ($column, $definition) {
                    $table->string($column, $definition['length'])->nullable()->after($definition['after']);
                });
            }
        }

        if (!$this->indexExists('orders', 'orders_payment_country_created_index')) {
            Schema::table('orders', fn (Blueprint $table) => $table->index(['payment_country', 'created_at'], 'orders_payment_country_created_index'));
        }
        if (!$this->indexExists('orders', 'orders_delivery_location_index')) {
            Schema::table('orders', fn (Blueprint $table) => $table->index(['delivery_country', 'division', 'district'], 'orders_delivery_location_index'));
        }
        if (!$this->indexExists('orders', 'orders_area_index')) {
            Schema::table('orders', fn (Blueprint $table) => $table->index('area', 'orders_area_index'));
        }

        DB::table('orders')->whereNull('payment_country')->update(['payment_country' => 'Unknown']);
        DB::table('orders')->whereNull('delivery_country')->update([
            'delivery_country' => 'Bangladesh',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
        ]);
        DB::statement("UPDATE orders SET area = LEFT(SUBSTRING_INDEX(address, ', ', -1), 150) WHERE area IS NULL AND address IS NOT NULL");
    }

    private function indexExists(string $table, string $index): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $index)
            ->exists();
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_payment_country_created_index');
            $table->dropIndex('orders_delivery_location_index');
            $table->dropIndex('orders_area_index');
            $table->dropColumn(['payment_country', 'delivery_country', 'division', 'district', 'area']);
        });
    }
};
