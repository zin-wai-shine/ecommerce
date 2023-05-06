<?php

namespace App\Services\Auth\Admin\ManagePermission;

use App\Exceptions\RouteNotFoundException;
use App\Models\NovaRoute;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\JsonResponse;

class RolePermissionService
{
    public static function store($role,$request) :JsonResponse|RouteNotFoundException
    {
        $route_id = $request->route_id;
        $route_exists = NovaRoute::findOrFail($route_id);
        if($route_exists)
        {
            $route = NovaRoute::where('id',$route_id)->first();
            $permission_route = Permission::where('name',$route->uri)->first();

            RolePermission::updateOrCreate([
                'role_id' => $role->id,
                'permission_id' => $permission_route->id
            ]);

            return response()->json([
                'message' => 'permission (' . $permission_route->name . ') was added permission access to Role' . $role->name
            ]);
        }

        throw new RouteNotFoundException('nova routes not found');

    }


    public static function permissionStatusToRoute($request)
    {
        $routes = $request->routes;
        $permission =  $request->permission;

        NovaRoute::whereIn('id',$routes)
        ->update(['require_permission' => $permission]);

        return response()->json([
            'message' => 'permissions was changed successfully'
        ]);
    }
}
