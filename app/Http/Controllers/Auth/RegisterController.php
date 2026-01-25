<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Libraries\Divisions;
use App\Libraries\Positions;
use App\Libraries\Sections;
use App\Libraries\Steps;
use App\Libraries\Windows;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function index()
    {
        $divisions = Divisions::all();
        $sections = [];
        $steps = [];

        if (old('divisionId')) {
            $sections = Sections::where('division_id', old('divisionId'))
                ->orderBy('section_name')
                ->get();
        }
        if (old('sectionId')) {
            $steps = Steps::where('section_id', old('sectionId'))
                ->orderBy('step_number')
                ->get();
        }

        return view('auth.register', compact('divisions', 'sections', 'steps'))
            ->with([
                'positions' => Positions::all(),
                'window' => Windows::all(),
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

    public function sectionsByDivision($divisionId)
    {
        // Fetch sections belonging to the selected division
        $sections = DB::table('sections')
            ->where('division_id', $divisionId)
            ->orderBy('section_name')
            ->get(['id', 'section_name']);

        return response()->json($sections);
    }

    public function stepsBySection($sectionId)
    {
        // Fetch steps belonging to the selected section
        $steps = DB::table('steps')
            ->where('section_id', $sectionId)
            ->orderBy('step_number')
            ->get(['id', 'step_number']);

        return response()->json($steps);
    }
}
