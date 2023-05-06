<?php
namespace App\Traits;

use hisorange\BrowserDetect\Parser as Browser;


trait GetClientInfo
{
    public function getInfo($request)
    {
        $data = [];
        $ipAddress = $request->ip();
        $deviceType =  Browser::deviceType() ?? null;
        $user_agent = Browser::userAgent() ?? null;
        $browser_agent = Browser::browserName() ?? null;
        $device_type = Browser::deviceType() ?? null;
        $platform_name = Browser::platformFamily() ?? null;

        $data = array(
            'ip' => $ipAddress,
            'device' => $deviceType,
            'user_agent' => $user_agent,
            'browser_agent' => $browser_agent,
            'device_type' => $device_type,
            'platform_name' => $platform_name,
        );
        return $data;

    }
}
