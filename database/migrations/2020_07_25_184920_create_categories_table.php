<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('icon_image_id')->nullable();
            $table->unsignedBigInteger('banner_image_id')->nullable();
            $table->integer('position');
            $table->string('slug');
            $table->foreignId('parent_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->boolean('is_customized')->default(0);
            $table->timestamps();

            $table->foreign('icon_image_id')->references('id')->on('images')->nullOnDelete();
            $table->foreign('banner_image_id')->references('id')->on('images')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
