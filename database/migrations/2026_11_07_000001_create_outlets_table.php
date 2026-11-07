<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('phone', 100)->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $outlets = json_decode(file_get_contents(database_path('data/outlets.json')), true, 512, JSON_THROW_ON_ERROR);
        foreach ($outlets as $outlet) {
            DB::table('outlets')->insert($outlet + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
