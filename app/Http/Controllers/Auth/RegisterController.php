<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Libraries\Divisions;
use App\Libraries\Positions;
use App\Libraries\Sections;
use App\Libraries\Steps;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register', [
            'divisions' => Divisions::all(),
            'sections' => Sections::all(),
            'positions' => Positions::all(),
            'steps' => Steps::all(),

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

        $divisionName = $user->division->division_name;
        $sectionName = $user->section->section_name;

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
}
