<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $icons = ['fa-book','fa-cart','fa-discount','fa-cross'];

        foreach($icons as $icon)
        {
           Icon::factory()->create([
            'icon_type' => 'fas',
            'icon_name' => $icon
           ]);
        }
    }
}
