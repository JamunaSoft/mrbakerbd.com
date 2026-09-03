<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('site_title')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->longText('address')->nullable();
            $table->string('admin_url')->nullable();
            $table->string('site_url')->nullable();
            $table->string('date_format');
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->boolean('home_delivery')->default(1);
            $table->double('delivery_charge')->nullable();
            $table->boolean('local_pickup')->default(0);
            $table->boolean('payment_cod')->default(1);
            $table->boolean('payment_sslc')->default(1);
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
        Schema::dropIfExists('settings');
    }
}
