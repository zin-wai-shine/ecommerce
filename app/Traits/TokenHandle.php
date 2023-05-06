<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait TokenHandle
{
    public function generateToken($user)
    {
        return  $user->createToken('Admin Token')->plainTextToken;
    }

    public function deleteCurrentToken()
    {
        return Auth::user()->currentAccessToken()->delete();
    }

    public function deleteAllTokens($user) :object
    {
        return $user()->tokens()->delete();
    }

}
