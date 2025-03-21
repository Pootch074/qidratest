<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        return view((Str::lower(auth()->user()->getUserTypeName())) . '/dashboard');
    }
}
