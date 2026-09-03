<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First check if the images table exists
        if (!Schema::hasTable('images')) {
            Schema::create('images', function (Blueprint $table) {
                $table->id();
                $table->string('path');
                $table->string('name')->nullable();
                $table->string('alt')->nullable();
                $table->string('title')->nullable();
                $table->integer('width')->nullable();
                $table->integer('height')->nullable();
                $table->integer('size')->nullable();
                $table->string('mime_type')->nullable();
                $table->timestamps();
            });
        } else {
            // Add missing columns if they don't exist
            Schema::table('images', function (Blueprint $table) {
                if (!Schema::hasColumn('images', 'name')) {
                    $table->string('name')->nullable()->after('path');
                }
                if (!Schema::hasColumn('images', 'alt')) {
                    $table->string('alt')->nullable()->after('name');
                }
                if (!Schema::hasColumn('images', 'title')) {
                    $table->string('title')->nullable()->after('alt');
                }
                if (!Schema::hasColumn('images', 'width')) {
                    $table->integer('width')->nullable()->after('title');
                }
                if (!Schema::hasColumn('images', 'height')) {
                    $table->integer('height')->nullable()->after('width');
                }
                if (!Schema::hasColumn('images', 'size')) {
                    $table->integer('size')->nullable()->after('height');
                }
                if (!Schema::hasColumn('images', 'mime_type')) {
                    $table->string('mime_type')->nullable()->after('size');
                }
            });
        }
    }

    public function down(): void
    {
        // We don't want to drop the table or columns in down()
        // as it might affect existing data
    }
};
