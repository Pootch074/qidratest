<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

            $user = User::find(Auth::id());

            if ($user->is_logged_in && $user->session_id && $user->session_id !== session()->getId()) {
                try {
                    \Session::getHandler()->destroy($user->session_id);
                } catch (\Exception $e) {
                    \Log::warning("Failed to destroy old session for user {$user->id}: {$e->getMessage()}");
                }
            }

            $user->is_logged_in = true;
            $user->session_id = session()->getId();
            $user->save();

            $user = User::with(['window.step.section.division.office'])->find(Auth::id());

            $section  = optional(optional(optional($user->window)->step)->section);
            $division = optional($section->division);
            $office   = optional($division->office);

            $request->session()->put('window_number', $user->window->window_number ?? null);
            $request->session()->put('step_number', $user->window->step->step_number ?? null);
            $request->session()->put('section_name', $section->section_name ?? null);
            $request->session()->put('division_name', $division->division_name ?? null);
            $request->session()->put('field_office', $office->field_office ?? null);

            switch ($user->user_type) {
                case 0:
                    return redirect()->route('superadmin');
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


    public function logout(Request $request)
{
    $user = Auth::user();
    if ($user) {
        $user->session_id = null;
        $user->is_logged_in = false; 
        $user->save();
    }

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($request->expectsJson()) {
        return response()->json(['message' => 'Logged out']);
    }

    return redirect()->route('login');
}

}
