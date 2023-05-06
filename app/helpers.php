<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

if (!function_exists('get_api_routes')) {
    function get_api_routes()
    {
        $collection = [];
        $excludes = ['api/v1/customer','api/v1'];
        $routes = collect(Route::getRoutes())
            ->filter(function ($route) use ($excludes) {
                $controller = str_contains($route->getAction('uses'), 'App\Http\Controllers\Api');
                $not_customer = str_contains($route->getAction('uses'), 'App\Http\Controllers\Api\Customers');
                 $prefix = $route->getPrefix();

                return $route->getAction('uses') && $controller && !$not_customer
                ;
            })
            ->map(function ($route) {
                $exclude = ['api/v1/'];

                $modified_route = str_replace($exclude,'',$route->uri());

                $route_name = str_replace('/','.',$modified_route);

                return [
                    'uri' => $modified_route,
                    'prefix' => $route->getPrefix(),
                    // 'name' => $route->getName(),
                    'method' => implode('|', $route->methods()),
                    'action' => $route->getAction('uses'),
                    'route_name' => $route_name,
                ];
            });

            foreach($routes as $k=>$route)
            {
                $collection[] = $route;
            }
        return $collection;
    }
}

if(!function_exists('getCountExcel'))
{
    /**
     * Method getCountExcel
     *
     * @param $file $file [ file that want to count]
     *
     * @return int
     */
    function getCountExcel($file)
    {
        $rows = Excel::toArray([], $file);
        $rowCount = count($rows[0]);
        return $rowCount;
    }
}

if(!function_exists('createStorage'))
{
    function storageCreate(string $storagePathName)
    {
        $path = 'public/' .$storagePathName;
        Storage::makeDirectory($path,0777,true);
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path,0777,true);
        }
        return $path;
    }
}
