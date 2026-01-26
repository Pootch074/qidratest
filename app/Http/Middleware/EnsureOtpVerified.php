<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOtpVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is not logged in, let 'auth' middleware handle it
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        // If OTP is not verified, redirect to OTP page
        if (session('otp_verified') !== true) {
            // allow OTP page itself to be accessed
            if (! $request->routeIs('login.show.otp') && ! $request->routeIs('login.verify.otp')) {
                return redirect()->route('login.show.otp');
            }
        }

        return $next($request);
    }
}
