<?php

namespace App\Http\Controllers;

use App\Models\Lgu;
use App\Models\Province;
use App\Models\Region;
use App\Models\User;

class UsersController extends Controller
{
    public function preassess()
    {
        return view('preassess.index');
    }
    public function admin()
    {
        return view('admin.index');
    }
}
