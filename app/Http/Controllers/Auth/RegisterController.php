<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Division;
use App\Models\Position;
use App\Models\Section;
use App\Models\Step;
use App\Models\User;
use App\Models\Window;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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

        // Create the user and assign OTP in one step
        $user = tap(User::create([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'division_id' => $data['divisionId'],
            'section_id' => $data['sectionId'],
            'position' => $data['position'],
            'email' => $data['email'],
            'step_id' => $data['stepId'],
            'window_id' => $data['windowId'],
            'assigned_category' => $data['category'],
            'status' => User::STATUS_INACTIVE,
        ]), function ($user) {
            $user->otp_code = rand(100000, 999999);
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();
        });

        // Load related division and section if you need their names
        $user->load(['division', 'section']);

        // Store user id in session for OTP verification
        session(['otp_user_id' => $user->id]);

        // Send OTP email
        Mail::to($user->email)->send(new \App\Mail\SendOtpMail($user));

        // Redirect to OTP verification page
        return redirect()->route('register.show.otp')->with('success', 'OTP sent to your email.');
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

    public function registerShowOtp()
    {
        $userId = session('otp_user_id');

        if (! $userId) {
            return redirect()->route('login')->withErrors(['otp_code' => 'OTP session not found.']);
        }

        $user = User::find($userId);

        if (! $user || ! $user->otp_expires_at) {
            return redirect()->route('login')->withErrors(['otp_code' => 'OTP session invalid or expired.']);
        }

        return view('auth.registerotp', [
            'otpExpiresAt' => $user->otp_expires_at->timestamp,
        ]);
    }

    public function registerVerifyOtp(Request $request)
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

        return redirect()->route('login')->with('success', 'Your account has been verified. Please wait for the administrator’s approval to log in successfully.');
    }
}
