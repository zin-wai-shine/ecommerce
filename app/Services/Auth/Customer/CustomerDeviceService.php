<?php

namespace App\Services\Auth\Customer;

use App\Http\Controllers\CustomClass\Detect;
use App\Http\Resources\CustomerRegisterResource;
use App\Models\CustomerDevice;

class CustomerDeviceService
{

    public static function create($request,$userAgent,$customer,$token,$type)
    {
        CustomerDevice::create([
            'action_type'=>$type,
            'device' =>Detect::getDeviceType($userAgent) .' / '.Detect::browser() . ' / ' . implode(" / ",Detect::systemInfo()),
            'date'=> now(),
            'ip'=> $request->ip(),
            'customer_id'=>$customer->customer_id
        ]);


    }
}
