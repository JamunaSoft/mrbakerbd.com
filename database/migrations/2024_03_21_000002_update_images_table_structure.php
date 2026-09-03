<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('images', function (Blueprint $table) {
            // Add new name column after path
            $table->string('name')->after('path');
        });
    }

    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            // Drop only the new name column
            $table->dropColumn('name');
        });
    }
};
