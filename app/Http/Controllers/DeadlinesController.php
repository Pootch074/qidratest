<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeadlinesController extends Controller
{
    //
    public function index()
    {
        return view('rmt.deadlines.index');
    }
}
