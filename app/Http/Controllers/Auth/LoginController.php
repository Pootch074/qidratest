<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = User::find(Auth::id());

            // BLOCK LOGIN if user status = 1
            if ($user->status === User::STATUS_INACTIVE) { // or === 1 if you prefer literal
                Auth::logout(); // immediately log out
                return back()->withErrors([
                    'email' => 'Login denied: Your account is pending or blocked.',
                ])->onlyInput('email');
            }

            if ($user->is_logged_in && $user->session_id && $user->session_id !== session()->getId()) {
                try {
                    Session::getHandler()->destroy($user->session_id);
                } catch (\Exception $e) {
                    Log::warning("Failed to destroy old session for user {$user->id}: {$e->getMessage()}");
                }
            }

            $user->is_logged_in = true;
            $user->session_id = session()->getId();
            $user->save();

            $user = User::with(['window.step.section.division.office'])->find(Auth::id());

            $section = optional(optional(optional($user->window)->step)->section);
            $division = optional($section->division);
            $office = optional($division->office);

            $request->session()->put('window_number', $user->window->window_number ?? null);
            $request->session()->put('step_number', $user->window->step->step_number ?? null);
            $request->session()->put('section_name', $section->section_name ?? null);
            $request->session()->put('division_name', $division->division_name ?? null);
            $request->session()->put('office_name', $office->office_name ?? null);

            switch ($user->user_type) {
            case User::TYPE_SUPERADMIN:
                return redirect()->route('superadmin');
            case User::TYPE_ADMIN:
                return redirect()->route('admin');
            case User::TYPE_IDSCAN:
                return redirect()->route('idscan');
            case User::TYPE_PACD:
                return redirect()->route('pacd');
            case User::TYPE_USER:
                return redirect()->route('user');
            case User::TYPE_DISPLAY:
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
