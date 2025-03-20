<?php

namespace App\Http\Controllers;

use App\Models\Lgu;
use App\Models\Province;
use App\Models\Region;
use App\Models\User;

class UsersController extends Controller
{
    //
    public function index()
    {
        $userTypes = User::getUserTypes();
        $lgus = Lgu::all();
        return view('admin.users.index', compact('userTypes', 'lgus'));
    }

    public function rmt()
    {
        return view('admin.rmt.index');
    }

    public function lgu()
    {
        $regions = Region::all();
        $provinces = Province::all();
        $lguTypes = Lgu::getLguTypes();
        return view('admin.lgu.index', compact('regions', 'provinces', 'lguTypes'));
    }
}
