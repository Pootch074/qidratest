<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // If user has a different active session, force logout
            if ($user->session_id && $user->session_id !== session()->getId()) {
                Auth::logout();

                // Clear session safely
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // 🚨 Add a flag so we know this was an auto logout (not manual)
                return redirect()->route('login')->with('forced_logout', true);
            }
        }

        return $next($request);
    }
}
