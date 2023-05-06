<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CustomerDevice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Filesystem\Filesystem;

class DatabaseSeeder extends Seeder
{
    //some changes
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            IconSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,

            CustomerSeeder::class,
            CustomerDeviceSeeder::class,
            AddToCartSeeder::class
        ]);

        $this->call([ReviewSeeder::class]);

        \App\Models\Admin::factory()->create([
            'name' => 'Test User',
            'email' => 'hsh@gmail.com',
            'role_id' => 1,
            'password' => Hash::make('asdffdsa'),
        ]);

        \App\Models\Admin::factory()->create([
            'name' => 'Seller',
            'email' => 'market@gmail.com',
            'role_id' => 2,
            'password' => Hash::make('asdffdsa'),
        ]);

        \Illuminate\Support\Facades\Artisan::call('route:autoload');
        \Illuminate\Support\Facades\Artisan::call('sync:product');

        $file = new FileSystem;
        $file->cleanDirectory('storage/app/public/');

        echo "\e[93mStorage Cleaned \n";


    }
}
