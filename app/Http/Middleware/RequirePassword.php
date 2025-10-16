<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequirePassword
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->passwordConfirmed()) {
            return $next($request);
        }

        return redirect()->route('password.confirm');
    }
}
