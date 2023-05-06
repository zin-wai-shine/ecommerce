<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\AdminRegisterRequest;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Services\Auth\Admin\LoginService;
use App\Services\Auth\Admin\RegisterService;
use App\Traits\GetClientInfo;
use App\Traits\ResponseJson;
use App\Traits\TokenHandle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminAuthController extends Controller
{
    use ResponseJson,TokenHandle,GetClientInfo;

    private $registerService;
    private $loginService;

    public function __construct(RegisterService $registerService, LoginService $loginService)
    {
        $this->registerService = $registerService;
        $this->loginService = $loginService;
    }

    public function register(AdminRegisterRequest $request) :JsonResponse
    {
       return $this->registerService->register($request);
    }

    public function login(AdminRequest $request,) :JsonResponse
    {
        return $this->loginService->login($request);
    }

    public function logout() :JsonResponse
    {
        $this->deleteCurrentToken();

        return $this->responseJson([
            'message' => 'logout successfully!'
        ]);
    }

    public function profile() :JsonResponse
    {

        $user = Admin::userProfile();

        return $this->responseJson([
            "user" => $user,
        ]);
    }

    public function devices(Request $request)
    {
        return $this->getInfo($request);
    }

    public function getLoginHistory()
    {
        return $this->loginService->getLoginRecords();
    }

}
