<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, create the new table
        Schema::create('product_gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('image_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate data from old table to new table
        DB::statement('
            INSERT INTO product_gallery (product_id, image_id, created_at, updated_at)
            SELECT pi.product_id, i.id, pi.created_at, pi.updated_at
            FROM product_images pi
            JOIN images i ON i.path = pi.image
        ');

        // Drop the old table
        Schema::dropIfExists('product_images');
    }

    public function down(): void
    {
        // Recreate the old table
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Migrate data back
        DB::statement('
            INSERT INTO product_images (product_id, image, is_primary, created_at, updated_at)
            SELECT pg.product_id, i.path, false, pg.created_at, pg.updated_at
            FROM product_gallery pg
            JOIN images i ON i.id = pg.image_id
        ');

        // Drop the new table
        Schema::dropIfExists('product_gallery');
    }
};