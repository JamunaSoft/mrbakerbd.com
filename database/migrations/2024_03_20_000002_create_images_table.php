<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
