<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('ga4_measurement_id', 64)->nullable();
            $table->string('google_ads_id', 64)->nullable();
            $table->string('google_ads_conversion_label', 128)->nullable();
            $table->string('meta_pixel_ids')->default('');
            $table->string('tracking_delivery', 16)->default('website');
            $table->boolean('enhanced_conversions_enabled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['ga4_measurement_id', 'google_ads_id', 'google_ads_conversion_label', 'meta_pixel_ids', 'tracking_delivery', 'enhanced_conversions_enabled']);
        });
    }
};
