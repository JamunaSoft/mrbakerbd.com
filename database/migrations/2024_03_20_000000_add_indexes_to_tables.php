<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Products table indexes
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!$this->hasIndex('products', 'name')) {
                    $table->index('name');
                }
                if (!$this->hasIndex('products', 'slug')) {
                    $table->index('slug');
                }
                if (!$this->hasIndex('products', 'status')) {
                    $table->index('status');
                }
                if (!$this->hasIndex('products', 'featured')) {
                    $table->index('featured');
                }
                if (!$this->hasIndex('products', 'category_id')) {
                    $table->index('category_id');
                }
                if (!$this->hasIndex('products', ['status', 'featured'])) {
                    $table->index(['status', 'featured']);
                }
            });
        }

        // Categories table indexes
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!$this->hasIndex('categories', 'name')) {
                    $table->index('name');
                }
                if (!$this->hasIndex('categories', 'slug')) {
                    $table->index('slug');
                }
                if (!$this->hasIndex('categories', 'status')) {
                    $table->index('status');
                }
                if (!$this->hasIndex('categories', 'parent_id')) {
                    $table->index('parent_id');
                }
            });
        }

        // Orders table indexes
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!$this->hasIndex('orders', 'customer_id')) {
                    $table->index('customer_id');
                }
                if (!$this->hasIndex('orders', 'status')) {
                    $table->index('status');
                }
                if (!$this->hasIndex('orders', 'created_at')) {
                    $table->index('created_at');
                }
                if (!$this->hasIndex('orders', ['customer_id', 'status'])) {
                    $table->index(['customer_id', 'status']);
                }
            });
        }

        // Order items table indexes
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!$this->hasIndex('order_items', 'order_id')) {
                    $table->index('order_id');
                }
                if (!$this->hasIndex('order_items', 'product_id')) {
                    $table->index('product_id');
                }
                if (!$this->hasIndex('order_items', ['order_id', 'product_id'])) {
                    $table->index(['order_id', 'product_id']);
                }
            });
        }

        // Users table indexes
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!$this->hasIndex('users', 'email')) {
                    $table->index('email');
                }
                if (!$this->hasIndex('users', 'active')) {
                    $table->index('active');
                }
                if (!$this->hasIndex('users', 'created_at')) {
                    $table->index('created_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Products table indexes
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if ($this->hasIndex('products', 'name')) {
                    $table->dropIndex(['name']);
                }
                if ($this->hasIndex('products', 'slug')) {
                    $table->dropIndex(['slug']);
                }
                if ($this->hasIndex('products', 'status')) {
                    $table->dropIndex(['status']);
                }
                if ($this->hasIndex('products', 'featured')) {
                    $table->dropIndex(['featured']);
                }
                if ($this->hasIndex('products', 'category_id')) {
                    $table->dropIndex(['category_id']);
                }
                if ($this->hasIndex('products', ['status', 'featured'])) {
                    $table->dropIndex(['status', 'featured']);
                }
            });
        }

        // Categories table indexes
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if ($this->hasIndex('categories', 'name')) {
                    $table->dropIndex(['name']);
                }
                if ($this->hasIndex('categories', 'slug')) {
                    $table->dropIndex(['slug']);
                }
                if ($this->hasIndex('categories', 'status')) {
                    $table->dropIndex(['status']);
                }
                if ($this->hasIndex('categories', 'parent_id')) {
                    $table->dropIndex(['parent_id']);
                }
            });
        }

        // Orders table indexes
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if ($this->hasIndex('orders', 'customer_id')) {
                    $table->dropIndex(['customer_id']);
                }
                if ($this->hasIndex('orders', 'status')) {
                    $table->dropIndex(['status']);
                }
                if ($this->hasIndex('orders', 'created_at')) {
                    $table->dropIndex(['created_at']);
                }
                if ($this->hasIndex('orders', ['customer_id', 'status'])) {
                    $table->dropIndex(['customer_id', 'status']);
                }
            });
        }

        // Users table indexes
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if ($this->hasIndex('users', 'email')) {
                    $table->dropIndex(['email']);
                }
                if ($this->hasIndex('users', 'active')) {
                    $table->dropIndex(['active']);
                }
                if ($this->hasIndex('users', 'created_at')) {
                    $table->dropIndex(['created_at']);
                }
            });
        }
    }

    /**
     * Check if an index exists on a table
     */
    private function hasIndex(string $table, $columns): bool
    {
        if (!Schema::hasTable($table)) {
            return false;
        }

        $indexName = is_array($columns)
            ? $table . '_' . implode('_', $columns) . '_index'
            : $table . '_' . $columns . '_index';

        $indexes = DB::select("SHOW INDEXES FROM `{$table}`");

        foreach ($indexes as $index) {
            if ($index->Key_name === $indexName) {
                return true;
            }
        }

        return false;
    }
};
