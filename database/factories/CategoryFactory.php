<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'name' => $name,
            'description' => $this->faker->paragraph,
            'icon' => null,
            'image' => null,
            'position' => $this->faker->numberBetween(1, 100),
            'slug' => Str::slug($name),
            'parent_id' => null,
            'status' => $this->faker->boolean,
            'meta_description' => $this->faker->sentence,
            'meta_keywords' => $this->faker->words(5, true),
        ];
    }

    public function withParent(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Category::factory(),
            ];
        });
    }
}
