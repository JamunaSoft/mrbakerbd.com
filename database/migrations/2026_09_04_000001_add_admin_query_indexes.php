<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!$this->indexExists('orders', 'orders_status_id_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index(['status', 'id'], 'orders_status_id_index');
            });
        }

        if (!$this->indexExists('orders', 'orders_created_at_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index('created_at', 'orders_created_at_index');
            });
        }

        if (!$this->indexExists('products', 'products_status_featured_index')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['status', 'featured'], 'products_status_featured_index');
            });
        }

        if (!$this->indexExists('products', 'products_slug_index')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('slug', 'products_slug_index');
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('orders', 'orders_status_id_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('orders_status_id_index');
            });
        }

        if ($this->indexExists('orders', 'orders_created_at_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('orders_created_at_index');
            });
        }

        if ($this->indexExists('products', 'products_status_featured_index')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_status_featured_index');
            });
        }

        if ($this->indexExists('products', 'products_slug_index')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_slug_index');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $index)
            ->exists();
    }
};
