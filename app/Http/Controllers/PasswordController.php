<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function passwordRequest()
    {
        return view('auth.forgot-password');
    }

    public function passwordEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {

                /**
                 * 🔐 Kill ALL active DB sessions for this user
                 */
                DB::table('sessions')->where('user_id', $user->id)->delete();

                /**
                 * 🔄 Reset auth, OTP, and login state
                 */
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'is_logged_in' => false,
                    'session_id' => null,
                    'otp_code' => null,
                    'otp_expires_at' => null,
                ])->save();

                /**
                 * 🧹 Clear any existing OTP session
                 */
                session()->forget(['otp_user_id', 'otp_code', 'otp_expires_at', 'otp_verified', 'login_log_id']);

                /**
                 * 🚨 Logout from other devices (optional)
                 */
                Auth::logoutOtherDevices($password);

                event(new PasswordReset($user));
            }
        );
        dd($status);

        /**
         * 🔥 Clear current browser session
         */
        session()->invalidate();
        session()->regenerateToken();

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password reset successful. Please login again.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
