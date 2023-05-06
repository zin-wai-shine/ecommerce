<?php

namespace App\Http\Controllers\Api;

use App\Enum\ProductStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\NovaRoute;
use Illuminate\Support\Facades\Route;

use App\Models\Role;
use App\Traits\ResponseJson;
use App\Traits\UsePhoto;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\map;

class TestApiController extends Controller
{
    use ResponseJson,UsePhoto;
    public function index()
    {
        return $this->responseJson(['message' => 'hi']);
    }

    public function test()
    {
        $value = 0;
        $commands = get_api_routes();

        return $commands;

    }

    public function san()
    {
        $auth_permissions = Role::getPermissionArray();
        return $auth_permissions;
    }

}
