<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\Positions;
use App\Libraries\Offices;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register', [
            'positions' => Positions::all(),
            'offices' => Offices::all(),
        ]);
    }
}
