<?php

namespace App\Services\Auth\Admin;

class LoginAtService
{
    public static function create($agent)
    {
        $login = $agent->logins()->create([
            'login_at' => now(),
        ]);

        return $login;
    }

}
