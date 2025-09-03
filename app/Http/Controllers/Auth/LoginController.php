<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {
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

            // Get authenticated user with window + step
            $user = User::with(['window.step'])->find(Auth::id());
            // ✅ Load user with office/division/section chain
            $user = User::with('window.step.section.division.office')->find(Auth::id());


            $section  = optional(optional(optional($user->window)->step)->section);
            $division = optional($section->division);
            $office   = optional($division->office);

            // Save window_number and step_number into session
            $request->session()->put('window_number', $user->window->window_number ?? null);
            $request->session()->put('step_number', $user->window->step->step_number ?? null);

            // ✅ Store in session for later use in views
            $request->session()->put('section_name',   $section->section_name ?? null);
            $request->session()->put('division_name',  $division->division_name ?? null);
            $request->session()->put('field_office',   $office->field_office ?? null);

            // Redirect based on user_type
            switch ($user->user_type) {
                case 1:
                    return redirect()->route('admin');
                case 2:
                    return redirect()->route('idscan');
                case 3:
                    return redirect()->route('pacd');
                case 5:
                    return redirect()->route('user');
                case 6:
                    return redirect()->route('display');
                
                default:
                    return redirect()->route('login');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
