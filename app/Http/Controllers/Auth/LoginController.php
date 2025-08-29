<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Define the LoginController class extending the base Controller
class LoginController extends Controller
{
    // Show the login form
    public function login()
    {
        // Return the "auth.login" view
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on user_type
            switch ($user->user_type) {
                case 1: // Admin
                    return redirect()->route('admin');

                case 5: // Preassess
                    return redirect()->route('preassess');

                default: // All other roles
                    return redirect()->intended('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    // Handle logout
    public function logout(Request $request): RedirectResponse
    {
        // Log the user out
        Auth::logout();

        // Invalidate the current session
        $request->session()->invalidate();

        // Regenerate CSRF token for security
        $request->session()->regenerateToken();

        // Redirect user to homepage
        return redirect('/');
    }
}
