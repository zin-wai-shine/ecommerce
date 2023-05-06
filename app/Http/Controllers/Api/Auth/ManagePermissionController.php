<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\NovaRoute;
use App\Models\Role;
use App\Services\Auth\Admin\ManagePermission\RolePermissionService;
use App\Traits\ResponseJson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManagePermissionController extends Controller
{
    use ResponseJson;

    public function index(Role $role) :JsonResponse
    {
        $role_id = $role->id;
        $permissions = Role::getPermissionArray($role_id);
        return $this->responseJson(['permissions' => $permissions]);
    }

    public function store(Role $role,Request $request) :JsonResponse
    {
        $request->validate([
            'route_id'=>'required|numeric'
        ]);
        return RolePermissionService::store($role,$request);

    }

    public function requirePermission(Request $request) :JsonResponse
    {
        $request->validate([
            'permission'=>'required|numeric',
            'routes.*' => 'required|numeric'
        ]);
       return RolePermissionService::permissionStatusToRoute($request);
    }

    public function routeList() :JsonResponse
    {
        $routes = NovaRoute::select('id','uri','require_permission')->orderBy('id','desc')->get();
        return $this->responseJson(['routes' => $routes]);
    }
}
