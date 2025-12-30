<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $user = Auth::guard('admin')->user();
        if (
            $user &&
            $user->two_factor_secret &&               // 2FA enabled
            !session('admin_2fa_passed') &&            // NOT verified this session
            !$request->routeIs('admin.2fa.*') &&
            !$request->routeIs('admin.logout')
        ) {
            return redirect()->route('admin.2fa.two-factor-challenge');
        }

        return $next($request);
    }
}




