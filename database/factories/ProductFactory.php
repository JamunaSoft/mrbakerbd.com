<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'sku' => $this->faker->unique()->bothify('SKU-####-????'),
            'category_id' => Category::factory(),
            'featured' => $this->faker->boolean(20),
            'status' => true,
            'meta_description' => $this->faker->sentence,
            'meta_keywords' => implode(', ', $this->faker->words(5)),
        ];
    }

    public function featured(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'featured' => true,
            ];
        });
    }

    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => false,
            ];
        });
    }

    public function outOfStock(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'stock' => 0,
            ];
        });
    }
}
