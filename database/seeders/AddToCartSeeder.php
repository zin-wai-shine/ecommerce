<?php

namespace Database\Seeders;

use App\Models\AddToCart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddToCartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AddToCart::factory()->count(50)->create();
    }
}
