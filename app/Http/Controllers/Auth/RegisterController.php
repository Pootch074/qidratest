<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Libraries\Divisions;
use App\Libraries\Positions;
use App\Libraries\Sections;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register', [
            'areaOfAssignment' => Divisions::all(),
            'sections' => Sections::all(),
            'positions' => Positions::all(),

        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'office_id' => $data['divisionId'],
            'section_id' => $data['sectionId'],
            'position' => $data['position'],
            'email' => $data['email'],
            'status' => User::STATUS_INACTIVE, // keep user inactive until OTP verification
        ]);

        // Generate OTP
        $otp = rand(100000, 999999); // 6-digit code
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5); // OTP valid for 5 mins
        $user->save();

        // Send OTP via email
        Mail::to($user->email)->send(new \App\Mail\SendOtpMail($user));

        // Redirect to a page to enter OTP
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
