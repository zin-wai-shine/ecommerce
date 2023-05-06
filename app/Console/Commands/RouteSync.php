<?php

namespace App\Console\Commands;

use App\Models\NovaRoute;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class RouteSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:autoload {--show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Route autoload';

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
        $this->info("Syncing Routing...");
        $this->call('optimize');
        $routes = get_api_routes();

        $existed_route = NovaRoute::where('name',array_column($routes,'route_name'))->pluck('name')->toArray();

        foreach($routes as $k=>$route)
        {
            if (!in_array($route['route_name'], $existed_route)) {
                NovaRoute::updateOrCreate([
                    'name' => $route['route_name'],
                    'uri' => $route['uri'],
                    'require_permission' => 0,
                ]);
            }
        }

        NovaRoute::whereNotIn('name', array_column($routes, 'route_name'))->delete();
        $this->info('Routes autoloaded successfully!');
        $this->call('sync:permissions');
    }
}
