<?php

namespace App\Http\Middleware;

use App\Models\NovaRoute;
use App\Models\Permission;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class Has_permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $process_permission =  Route::current()->uri();//current uri
        $auth_permissions = Role::getPermissionArray();

        $permission = [];

        $exclude = ['api/v1/'];

        $modified_route = str_replace($exclude,'',$process_permission);

        $current_route = NovaRoute::where('uri',$modified_route)->exists();
        if($current_route)
        {
            $cu = NovaRoute::where('uri',$modified_route)->first();
            if($cu->require_permission == 1)
            {
                $permission = Permission::where('name',$modified_route)->first()->id;
                if(!in_array($permission,$auth_permissions))
                {
                    return response('need permission to perform this route', 401,);

                }

            }
        }
        return $next($request);

    }
}
