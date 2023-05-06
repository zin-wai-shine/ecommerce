<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->text(10);
        $slug = Str::slug($title);
        return [
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->text(300),
            'price' => (int) fake()->numberBetween(10,100000),
            'discount' => fake()->randomElement([null,10,20,30,60,80,40,50,70,100,90,null]),
            'stock' => rand(20,100),
            'product_status' => fake()->randomElement([0,1]),
            'category_id' => Category::inRandomOrder()->first()->id,
            'avg_rating' => 0,
        ];
    }
}
