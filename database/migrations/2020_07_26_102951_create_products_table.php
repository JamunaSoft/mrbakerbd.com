<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('short_desc')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('slug');
            $table->tinyInteger('type');
            $table->double('regular_price')->nullable();
            $table->double('special_price')->nullable();
            $table->foreignId('category_id');
            $table->text('tags')->nullable();
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('availability')->default(1);
            $table->tinyInteger('review')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->integer('views')->default(0);
            $table->integer('sales')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
