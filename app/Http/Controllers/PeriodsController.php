<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodsController extends Controller
{
    //
    public function index()
    {
        $periods = Period::all();
        return view('admin.periods.index', compact('periods'));
    }
}
