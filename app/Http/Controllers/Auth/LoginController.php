<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = User::find(Auth::id());
            if ($user->is_logged_in && $user->session_id && $user->session_id !== session()->getId()) {
                try {
                    Session::getHandler()->destroy($user->session_id);
                } catch (\Exception $e) {
                    Log::warning("Failed to destroy old session for user {$user->id}: {$e->getMessage()}");
                }
            }
            // Block inactive accounts
            if ($user->status === User::STATUS_INACTIVE) {
                Auth::logout();
                return back()->withErrors(['email' => 'Account pending or blocked'])->onlyInput('email');
            }
            $user = Auth::user();
            // Generate OTP and store in session
            $otp = rand(100000, 999999);
            session([
                'otp_user_id' => $user->id,
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(5),
                'otp_verified' => false,
            ]);
            // After OTP is generated
            $loginLog = LoginLog::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'PENDING', // waiting for OTP
            ]);
            // Store login_log_id in session
            session(['login_log_id' => $loginLog->id]);
            // Temporarily log out user until OTP verified
            Auth::logout();
            // Send OTP email
            Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your Login OTP Code');
            });
            return redirect()->route('login.show.otp')->with('success', 'OTP sent to your email');
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }


    // public function authenticate(LoginRequest $request): RedirectResponse
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();

    //         $user = User::find(Auth::id());

    //         // BLOCK LOGIN if user status = 1
    //         if ($user->status === User::STATUS_INACTIVE) { // or === 1 if you prefer literal
    //             Auth::logout(); // immediately log out

    //             return back()->withErrors([
    //                 'email' => 'Login denied: Your account is pending or blocked.',
    //             ])->onlyInput('email');
    //         }

    //         if ($user->is_logged_in && $user->session_id && $user->session_id !== session()->getId()) {
    //             try {
    //                 Session::getHandler()->destroy($user->session_id);
    //             } catch (\Exception $e) {
    //                 Log::warning("Failed to destroy old session for user {$user->id}: {$e->getMessage()}");
    //             }
    //         }

    //         $user->is_logged_in = true;
    //         $user->session_id = session()->getId();
    //         $user->save();

    //         $user = User::with(['window.step.section.division.office'])->find(Auth::id());

    //         $section = optional(optional(optional($user->window)->step)->section);
    //         $division = optional($section->division);
    //         $office = optional($division->office);

    //         $request->session()->put('window_number', $user->window->window_number ?? null);
    //         $request->session()->put('step_number', $user->window->step->step_number ?? null);
    //         $request->session()->put('section_name', $section->section_name ?? null);
    //         $request->session()->put('division_name', $division->division_name ?? null);
    //         $request->session()->put('office_name', $office->office_name ?? null);

    //         switch ($user->user_type) {
    //             case User::TYPE_SUPERADMIN:
    //                 return redirect()->route('superadmin');
    //             case User::TYPE_ADMIN:
    //                 return redirect()->route('admin');
    //             case User::TYPE_IDSCAN:
    //                 return redirect()->route('idscan');
    //             case User::TYPE_PACD:
    //                 return redirect()->route('pacd');
    //             case User::TYPE_USER:
    //                 return redirect()->route('user');
    //             case User::TYPE_DISPLAY:
    //                 return redirect()->route('display');
    //             default:
    //                 return redirect()->route('login');
    //         }
    //     }

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ])->onlyInput('email');
    // }

    public function loginVerifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otp = session('otp_code');
        $expiresAt = session('otp_expires_at');
        $userId = session('otp_user_id');
        $logId = session('login_log_id');

        /**
         * 1️⃣ OTP EXPIRED
         */
        if (! $otp || ! $userId || now()->gt($expiresAt)) {
            if ($logId) {
                LoginLog::where('id', $logId)->update([
                    'status' => 'FAILED',
                    'reason' => 'OTP expired',
                    'completed_at' => now(),
                ]);
            }

            session()->forget([
                'otp_user_id',
                'otp_code',
                'otp_expires_at',
                'otp_verified',
                'login_log_id',
            ]);

            return redirect()->route('login')
                ->withErrors(['email' => 'OTP expired. Please login again']);
        }

        /**
         * 2️⃣ OTP INVALID
         */
        if ($request->otp != $otp) {

            if ($logId) {
                LoginLog::where('id', $logId)->update([
                    'status' => 'FAILED',
                    'reason' => 'Invalid OTP',
                    'completed_at' => now(),
                ]);
            }

            return back()->withErrors(['otp' => 'Invalid OTP code']);
        }

        /**
         * 3️⃣ OTP SUCCESS
         */
        $user = User::find($userId);
        Auth::login($user);

        if ($logId) {
            LoginLog::where('id', $logId)->update([
                'status' => 'SUCCESS',
                'reason' => 'OTP verified',
                'completed_at' => now(),
            ]);
        }
        $user->is_logged_in = true;
        $user->session_id = session()->getId();
        $user->save();
        // Mark OTP as verified
        session(['otp_verified' => true]);

        session()->forget([
            'otp_code',
            'otp_expires_at',
            'otp_user_id',
            'login_log_id',
        ]);

        // Redirect by role
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

    public function loginShowOtp()
    {
        if (! session()->has('otp_user_id')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Please login first']);
        }

        return view('auth.loginotp', [
            'otpExpiresAt' => session('otp_expires_at')->timestamp,
        ]);
    }

    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');

        if (! $userId) {
            return redirect()->route('login')->withErrors(['email' => 'Please login first']);
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found']);
        }

        // Generate new OTP and update session
        $otp = rand(100000, 999999);
        session([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
            'otp_verified' => false,
        ]);

        // Send OTP email
        Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Login OTP Code');
        });

        return redirect()->route('login.show.otp')->with('success', 'A new OTP has been sent to your email.');
    }
}
