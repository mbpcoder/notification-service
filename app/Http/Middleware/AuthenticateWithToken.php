<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithToken
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Auth-Token');
        if ($token && Auth::guard('api')->attempt(['remember_token' => $token])) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
