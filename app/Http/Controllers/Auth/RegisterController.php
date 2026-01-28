<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\RegOtpMail;
use App\Models\Division;
use App\Models\Position;
use App\Models\Section;
use App\Models\Step;
use App\Models\User;
use App\Models\Window;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function index()
    {
        $divisions = Division::all();

        $sections = old('divisionId')
            ? Section::where('division_id', old('divisionId'))->orderBy('section_name')->get()
            : collect();

        $steps = old('sectionId')
            ? Step::where('section_id', old('sectionId'))->orderBy('step_number')->get()
            : collect();

        $windows = old('stepId')
            ? Window::where('step_id', old('stepId'))->orderBy('window_number')->get()
            : collect();

        return view('auth.register', compact(
            'divisions', 'sections', 'steps', 'windows'
        ))->with([
            'positions' => Position::all(),
            'categories' => UserCategory::cases(),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $registrationData = [
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'division_id' => $data['divisionId'],
            'section_id' => $data['sectionId'],
            'position' => $data['position'],
            'email' => $data['email'],
            'step_id' => $data['stepId'],
            'window_id' => $data['windowId'],
            'assigned_category' => $data['category'],
        ];
        session(['registration_data' => $registrationData]);

        // Gerate OTP
        $otp = rand(100000, 999999);
        $otpExpiresAt = now()->addMinutes(5);

        session([
            'otp_code' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        $regData = session('registration_data');
        $email = $regData['email'];
        $firstName = $regData['first_name'];
        $otp = session('otp_code');
        $otpUser = (object) [
            'email' => $email,
            'first_name' => $firstName,
            'otp_code' => $otp,
        ];
        Mail::to($email)->send(new RegOtpMail($otpUser));

        return redirect()->route('register.show.otp')->with('success', 'OTP sent to your email.');
    }

    public function registerVerifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $otpCode = session('otp_code');
        $registrationData = session('registration_data');
        $otpExpiresAt = session('otp_expires_at');

        if (! $registrationData || ! $otpCode || ! $otpExpiresAt) {
            return redirect()->route('register')->withErrors(['otp_code' => 'OTP session not found or expired.']);
        }

        if ($request->otp_code != $otpCode) {
            return back()->withErrors(['otp_code' => 'Invalid OTP code.']);
        }

        if (now()->gt($otpExpiresAt)) {
            return back()->withErrors(['otp_code' => 'Your OTP has expired. Please request a new one.']);
        }

        // OTP verified — now create the user
        $user = User::create($registrationData);

        // Optionally mark as verified or set status
        $user->email_is_verified = true;
        $user->save();

        // Clear session data
        session()->forget(['registration_data', 'otp_code', 'otp_expires_at']);

        return redirect()->route('login')->with('success', 'Your account has been verified. Please wait for admin approval to log in.');
    }

    public function registerShowOtp()
    {
        $registrationData = session('registration_data');
        $otpCode = session('otp_code');
        $otpExpiresAt = session('otp_expires_at');

        if (! $registrationData || ! $otpCode || ! $otpExpiresAt) {
            return redirect()->route('register')->withErrors(['otp_code' => 'OTP session not found or expired.']);
        }

        return view('auth.registerotp', [
            'otpExpiresAt' => $otpExpiresAt->timestamp,
            'email' => $registrationData['email'],
            'first_name' => $registrationData['first_name'],
        ]);
    }

    public function sectionsByDivision(Division $divisionId)
    {
        return response()->json(
            $divisionId->sections()->orderBy('section_name')->get(['id', 'section_name'])
        );
    }

    public function stepsBySection(Section $sectionId)
    {
        return response()->json(
            $sectionId->steps()->orderBy('step_number')->get(['id', 'step_name'])
        );
    }

    public function windowsByStep(Step $stepId)
    {
        return response()->json(
            $stepId->windows()->orderBy('window_number')->get(['id', 'window_number'])
        );
    }

    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');

        if (! $userId) {
            return redirect()->route('register')->withErrors(['otp_code' => 'OTP session not found.']);
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('register')->withErrors(['otp_code' => 'User not found.']);
        }

        // Generate new OTP
        $user->otp_code = rand(100000, 999999);
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Update session
        session([
            'otp_user_id' => $user->id,
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new \App\Mail\SendOtpMail($user));

        return redirect()->route('register.show.otp')->with('success', 'A new OTP has been sent to your email.');
    }
}
