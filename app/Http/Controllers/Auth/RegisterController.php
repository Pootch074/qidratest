<?php

namespace App\Http\Controllers\Auth;

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
        $user = User::create($data);

        return redirect()->route('login')->with('success', 'Account created successfully!');
    }
}
