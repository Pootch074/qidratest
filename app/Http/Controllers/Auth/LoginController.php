<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
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

        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Block inactive accounts
        if ($user->status === User::STATUS_INACTIVE) {
            Auth::logout();

            return back()->withErrors(['email' => 'Account pending or blocked'])->onlyInput('email');
        }

        // Generate OTP and store in session
        $otp = rand(100000, 999999);
        session([
            'otp_user_id' => $user->id,
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
            'otp_verified' => false,
        ]);

        // Temporarily log out user until OTP verified
        Auth::logout();

        // Send OTP email
        Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Login OTP Code');
        });

        return redirect()->route('showOtpForm')->with('success', 'OTP sent to your email');
    }

    // Show OTP form
    public function showOtpForm()
    {
        if (! session()->has('otp_user_id')) {
            return redirect()->route('login')->withErrors(['email' => 'Please login first']);
        }

        return view('auth.loginotp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otp = session('otp_code');
        $expiresAt = session('otp_expires_at');
        $userId = session('otp_user_id');

        if (! $otp || ! $userId || now()->gt($expiresAt)) {
            session()->forget(['otp_user_id', 'otp_code', 'otp_expires_at', 'otp_verified']);

            return redirect()->route('login')->withErrors(['email' => 'OTP expired. Please login again']);
        }

        if ($request->otp != $otp) {
            return back()->withErrors(['otp' => 'Invalid OTP code']);
        }

        // OTP correct, log the user in
        $user = User::find($userId);
        Auth::login($user);

        // Mark OTP as verified
        session(['otp_verified' => true]);

        // Remove OTP session data
        session()->forget(['otp_code', 'otp_expires_at', 'otp_user_id']);

        // Redirect user based on role
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
