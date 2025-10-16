<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role !== 'admin') {
            session()->flash('error', 'Admin access required.');
            return redirect()->route('account.profile');
        }
        return $next($request);
    }
}
