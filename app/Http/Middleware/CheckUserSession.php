<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // If session mismatch → user was logged in somewhere else
            if ($user->session_id !== session()->getId()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // 🧩 Handle AJAX or Livewire requests gracefully
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Your account was logged in from another device. Please login again.',
                    ], 440); // 440 = Login Timeout
                }

                // 🧭 Otherwise redirect normally
                return redirect('/')
                    ->withErrors(['email' => 'Your account was logged in from another device.']);
            }
        }

        return $next($request);
    }
}
