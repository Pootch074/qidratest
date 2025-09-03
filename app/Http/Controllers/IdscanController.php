<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IdscanController extends Controller
{
    public function index()
    {
        return view('idscan.index');
    }
}
