<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = ['create','update','delete','show'];

        foreach($permissions as $permission)
        {
           Permission::factory()->create([
           'module' => 'product',
           'name' => $permission
           ]);
        }
    }
}
