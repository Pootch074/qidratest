<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function show()
    {
        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        // Step 2: Find the user
        $user = User::where('otp_code', $request->otp_code)
                    ->first();

        if (!$user) {
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
