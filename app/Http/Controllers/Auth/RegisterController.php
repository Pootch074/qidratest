<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Division;
use App\Models\Section;
use App\Models\Step;
use App\Models\Window;
use App\Models\User;
use App\Models\Position;
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
        return redirect()->route('otp.verify')->with('success', 'OTP sent to your email.');
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
}
