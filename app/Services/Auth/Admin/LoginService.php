<?php

namespace App\Services\Auth\Admin;

use App\Models\Admin;
use App\Models\DeviceLogin;
use App\Models\Login;
use App\Traits\GetClientInfo;
use App\Traits\ResponseJson;
use App\Traits\TokenHandle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginService
{
    use ResponseJson,TokenHandle,GetClientInfo;

    protected function getAdminData($admin_id) :object
    {
        $admin = Admin::select(
                'admins.admin_id',
                'admins.name',
                'admins.email',
                'roles.name as role_name',
                'logins.login_at as login_at'
            )
            ->where('admin_id', $admin_id)
            ->join('roles', 'id', '=', 'admins.role_id')
            ->join('logins', 'loginable_id', '=', 'admins.admin_id')
            ->first();

        return $admin;
    }


    final public function login($request) :JsonResponse
    {
        $obj = new LoginService();
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            $admin = $request->user('admin');
            $token = $obj->generateToken($admin);

            $login_at = LoginAtService::create($admin);

            $user = $this->getAdminData($admin->admin_id);

            $this->saveDeviceTrace($request,$login_at->id);

            return $obj->responseJson([
                "message" => "Login successfully",
                "user" => $user,
                "token" => $token
            ]);
        }

        return $obj->responseJson(['missing' => 'These credentials do not match our records.']);
    }

    protected function saveDeviceTrace($request,$id) :void
    {
        $trace = $this->getInfo($request);
        DeviceLogin::create([
            'ip_address' => $trace['ip'],
            'loggged_id' => $id,
            'device' => $trace['device'],
            'os' => $trace['platform_name'],
            'browser_agent' => $trace['browser_agent'],
            'user_agent' =>$trace['user_agent'],
            'login_id' => $id,
        ]);
    }

    final public function getLoginRecords() :JsonResponse
    {

        $login_records = Login::select('id','login_at')
        ->where('loginable_id',Auth::user()->admin_id)
        ->latest('id')
        ->with('device')
        ->get();

        return $this->responseJson([
            'records' => $login_records,
        ]);
    }
}
