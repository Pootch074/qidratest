<?php

namespace App\Http\Controllers;

use App\Models\Lgu;
use App\Models\Province;
use App\Models\Region;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;


class UsersController extends Controller
{

    public function admin()
    {
        $user = auth()->user();
        $userColumns = [
            'first_name'       => 'First Name',
            'last_name'        => 'Last Name',
            'email'            => 'Email',
            'position'         => 'Position',
            'user_type'        => 'User Type',
            'assigned_category'=> 'Category',
            'window_id'        => 'Window ID',
        ];

        $users = User::where('section_id', $user->section_id)
            ->latest()
            ->get();



        $transactions = \App\Models\Transaction::orderBy('queue_number', 'desc')->get();
        return view('admin.index', compact('transactions', 'users', 'userColumns'));
    }
    public function users()
    {
        $userColumns = [
            'first_name'       => 'First Name',
            'last_name'        => 'Last Name',
            'email'            => 'Email',
            'position'         => 'Position',
            'user_type'        => 'User Type',
            'assigned_category'=> 'Category',
            'window_id'        => 'Window ID',
        ];

        $users = User::orderBy('id', 'desc')->get();
        return view('admin.users.table', compact('users', 'userColumns'));
    }



    public function pacd()
    {
        $sections = Section::orderBy('section_name')->get(['id','section_name']);
        return view('pacd.index', compact('sections'));
    }
    public function preassess()
    {
        return view('preassess.index');
    }
    public function encode()
    {
        return view('encode.index');
    }
    public function assessment()
    {
        return view('assessment.index');
    }
    public function release()
    {
        return view('release.index');
    }
    public function user()
    {
        return view('user.index');
    }

    


    // Store a new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'position' => 'nullable|string|max:255',
            'user_type' => 'required|integer',
            'assigned_category' => 'nullable|in:regular,priority',
            'window_id' => 'nullable|integer',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
