<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\TokenGuard;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->once(['api_token' => $request->bearerToken()])) {
            return $next($request);
        }

        return response('Unauthorized.', 401);
    }
}
