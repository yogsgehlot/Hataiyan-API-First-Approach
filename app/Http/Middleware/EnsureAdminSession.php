<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminSession
{
    public function handle(Request $request, Closure $next)
    {
         
        if (!session()->has('admin')) {
            return redirect()->route('admin.login');
        }
         return $next($request);
    }
}
