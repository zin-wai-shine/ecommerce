<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Console\Command;

class SyncPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync the routes with permissions to each role';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Syncing Permissions...");
        $routes = get_api_routes();

        $existed_permission = Permission::whereIn('name',array_column($routes, 'uri'))->pluck('name')->toArray();
        foreach ($routes as $k => $route) {
            if (!in_array($route['uri'], $existed_permission)) {
                Permission::updateOrCreate(['name' => $route['uri'], 'module' => '']);
            }
        }
        Permission::whereNotIn('name', array_column($routes, 'uri'))->delete();

        $super_admin = Role::canViewAll();
        $permissionCollection = Permission::all();
        foreach($permissionCollection as $permission)
        {
            if($super_admin)
            {
                RolePermission::updateOrCreate(['role_id'=>$super_admin->id,'permission_id'=>$permission->id]);
            }

        }

        $this->info('Permissions successfully synced!');

    }
}
