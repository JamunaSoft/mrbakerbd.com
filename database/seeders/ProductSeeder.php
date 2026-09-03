<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create 50 regular products
        Product::factory()->count(50)->create();

        // Create 10 featured products
        Product::factory()->count(10)->featured()->create();

        // Create 5 out of stock products
        Product::factory()->count(5)->outOfStock()->create();

        // Create 5 inactive products
        Product::factory()->count(5)->inactive()->create();
    }
}
