<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, update existing records to split path and filename
        $images = DB::table('images')->get();

        foreach ($images as $image) {
            $pathInfo = pathinfo($image->path);
            $newPath = $pathInfo['dirname'];
            $newName = $pathInfo['basename'];

            DB::table('images')
                ->where('id', $image->id)
                ->update([
                    'path' => $newPath,
                    'name' => $newName
                ]);
        }
    }

    public function down(): void
    {
        // Revert the changes by combining path and name
        $images = DB::table('images')->get();

        foreach ($images as $image) {
            $fullPath = $image->path . '/' . $image->name;

            DB::table('images')
                ->where('id', $image->id)
                ->update([
                    'path' => $fullPath,
                    'name' => $image->name
                ]);
        }
    }
};
