<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->string('email')->nullable();
            $table->string('delv_dt')->nullable();
            $table->longText('notes')->nullable();
            $table->integer('total_qty');
            $table->double('total_price');
            $table->string('coupon_code')->nullable();
            $table->double('discount')->nullable();
            $table->tinyInteger('shipping_method')->default(1);
            $table->double('shipping_charge')->nullable();
            $table->double('payable_amount');
            $table->tinyInteger('payment_method')->default(1);
            $table->double('amount')->nullable();
            $table->string('currency')->nullable();
            $table->tinyInteger('payment_status')->default(0);
            $table->string('status')->nullable();
            $table->string('source')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
