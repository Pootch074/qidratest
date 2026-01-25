<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function show()
    {
        $userId = session('otp_user_id');

        if (! $userId) {
            return redirect()->route('login')->withErrors(['otp_code' => 'OTP session not found.']);
        }

        $user = User::find($userId);

        if (! $user || ! $user->otp_expires_at) {
            return redirect()->route('login')->withErrors(['otp_code' => 'OTP session invalid or expired.']);
        }

        return view('auth.otp', [
            'otpExpiresAt' => $user->otp_expires_at->timestamp,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        // Step 2: Find the user
        $user = User::where('otp_code', $request->otp_code)
            ->first();

        if (! $user) {
            return back()->withErrors(['otp_code' => 'Invalid OTP code.']);
        }

        if ($user->otp_expires_at < Carbon::now()) {
            return back()->withErrors(['otp_code' => 'Your OTP has expired. Please request a new one.']);
        }

        // Mark user as verified
        $user->email_is_verified = true;
        // $user->status = User::STATUS_ACTIVE;
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Your account has been verified. Please wait for administrator approval before logging in.');
    }
}
