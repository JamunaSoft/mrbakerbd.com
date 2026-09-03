<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->string('image');
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });
        } else {
            // Add is_primary column if it doesn't exist
            Schema::table('product_images', function (Blueprint $table) {
                if (!Schema::hasColumn('product_images', 'is_primary')) {
                    $table->boolean('is_primary')->default(false)->after('image');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
