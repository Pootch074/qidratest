<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\Offices;
use App\Libraries\Sections;
use App\Libraries\Positions;

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
}
