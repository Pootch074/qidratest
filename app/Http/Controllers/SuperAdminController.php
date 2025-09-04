<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Section;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('user_type', User::TYPE_ADMIN);

        // 🔎 Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 📌 Filter by section
        if ($request->filled('section')) {
            $query->where('section_id', $request->section);
        }

        $admins = $query->latest()->get();

        // All sections for filter dropdown
        $sections = Section::orderBy('section_name')->get();

        return view('superadmin.index', compact('admins', 'sections'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'section'  => 'required|string|max:255',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']), // secure hashing
            'section'   => $validated['section'],
            'user_type' => 1, // hardcoded for admin
        ]);

        return redirect()->route('superadmin.index')->with('success', 'Admin user added successfully.');
    }
}
