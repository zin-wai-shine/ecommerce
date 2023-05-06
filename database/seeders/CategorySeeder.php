<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Icon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['food','fashion','alpha','sport shoes'];

        foreach($categories as $category)
        {
           Category::factory()->create([
            'title' => $category,
            'slug' => Str::slug($category),
            'icon_id' => Icon::inRandomOrder()->first()->id,
           ]);
        }
    }
}
