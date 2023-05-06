<?php

namespace App\Services\Auth\Admin;

use App\Models\Admin;
use App\Traits\ResponseJson;
use App\Traits\TokenHandle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    use ResponseJson,TokenHandle;

    final public function register($request) :JsonResponse
    {
        $obj = new RegisterService();

        $user = Admin::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
         ]);

        return $obj->responseJson([
            "success" => true,
            "message" => "$user->name is register successful.",
        ]);

    }
}
