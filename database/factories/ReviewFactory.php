<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $rate = range(1,5);
        $dummy_customers = range(1,10);
        return [
            'rate' => fake()->randomElement($rate),
            'comment' => fake()->text(30),
            'customer_id' => fake()->randomElement($dummy_customers),
            'product_id' => Product::inRandomOrder()->first()->id,
        ];
    }
}
