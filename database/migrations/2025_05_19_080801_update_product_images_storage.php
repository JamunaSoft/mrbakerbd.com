<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create images table if it doesn't exist
        if (!Schema::hasTable('images')) {
            Schema::create('images', function (Blueprint $table) {
                $table->id();
                $table->string('path');
                $table->string('alt')->nullable();
                $table->string('title')->nullable();
                $table->integer('width')->nullable();
                $table->integer('height')->nullable();
                $table->integer('size')->nullable();
                $table->string('mime_type')->nullable();
                $table->timestamps();
            });
        }

        // Add image_id column to products table if it doesn't exist
        if (!Schema::hasColumn('products', 'image_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('image_id')->nullable()->after('image')->constrained('images')->nullOnDelete();
            });
        }

        // Migrate existing image paths to the new storage system
        $products = DB::table('products')->whereNotNull('image')->get();
        foreach ($products as $product) {
            if (str_contains($product->image, '\\')) {
                // This is a temporary file path, we'll skip it
                continue;
            }

            // Create image record
            $imageId = DB::table('images')->insertGetId([
                'path' => $product->image,
                'alt' => pathinfo($product->image, PATHINFO_FILENAME),
                'title' => pathinfo($product->image, PATHINFO_FILENAME),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update product with new image_id
            DB::table('products')
                ->where('id', $product->id)
                ->update(['image_id' => $imageId]);
        }

        // Drop the old image column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the image column
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->after('image_id');
        });

        // Migrate data back
        $products = DB::table('products')
            ->join('images', 'products.image_id', '=', 'images.id')
            ->select('products.id', 'images.path')
            ->get();

        foreach ($products as $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update(['image' => $product->path]);
        }

        // Drop the image_id column
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
        });

        // Drop the images table
        Schema::dropIfExists('images');
    }
};
