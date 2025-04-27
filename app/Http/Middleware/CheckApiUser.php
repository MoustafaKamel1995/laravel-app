<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckApiUser
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role != 'api_user') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
