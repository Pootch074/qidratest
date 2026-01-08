<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuperAdminRequest;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class SuperAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('superadmin.index');
    }

    

    /**
     * Store a new admin user (user_type = 1).
     */
    public function store(StoreSuperAdminRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'position' => $validated['position'] ?? null,
            'section_id' => $validated['section_id'],
            'user_type' => User::TYPE_ADMIN,
        ]);

        return redirect()->route('superadmin.index')->with('success', 'Admin user added successfully.');
    }
}
