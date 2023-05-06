<?php

namespace Database\Factories;

use App\Models\AddToCart;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddToCart>
 */
class AddToCartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        static $used_product_ids = []; // keep track of used product IDs for each customer

        $customerIdArray = [1,2,3,4,5,6,7];
        if (count($used_product_ids[21] ?? []) < 5) {
            // if customer_id 21 has been used less than 5 times, select it
            $customer_id = 21;
        } else {
            // randomly select a customer_id from the remaining list
            $available_customer_ids = array_diff($customerIdArray, [21]);
            $customer_id = $available_customer_ids[array_rand($available_customer_ids)];
        }

        if (isset($used_product_ids[$customer_id])) {
            // if the customer has used products before, exclude them from the range of available products
            $available_product_ids = array_diff(range(1, 20), $used_product_ids[$customer_id]);
        } else {
            // if the customer has not used any products before, consider all products as available
            $available_product_ids = range(1, 20);
        }

        $product_id = $available_product_ids[array_rand($available_product_ids)];
        $used_product_ids[$customer_id][] = $product_id; // add the chosen product_id to the used IDs list for the customer

        return [
            'product_id' => $product_id,
            'product_name' => fake()->text(10),
            'customer_id' => $customer_id,
            'customer_name' => fake()->name,
            'price' => (int) fake()->numberBetween(10,100000),
            'item_count' => rand(1,20)
        ];



    }
}
