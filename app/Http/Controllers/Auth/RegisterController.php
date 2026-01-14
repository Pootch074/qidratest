<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Libraries\Offices;
use App\Libraries\Positions;
use App\Libraries\Sections;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register', [
            'offices' => Offices::all(),
            'sections' => Sections::all(),
            'positions' => Positions::all(),

        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'status' => User::STATUS_INACTIVE, // keep user inactive until OTP verification
    ]);

        // Generate OTP
        $otp = rand(100000, 999999); // 6-digit code
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(1); // OTP valid for 5 mins
        $user->save();

        // Send OTP via email
        Mail::to($user->email)->send(new \App\Mail\SendOtpMail($user));

         // Redirect to a page to enter OTP
        return redirect()->route('otp.verify')->with('success', 'OTP sent to your email.');
    }
}
