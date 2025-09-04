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
        $query = User::with('section')->admins(); // scope instead of raw where

        // 🔎 Search by first_name, last_name, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 📌 Filter by section
        if ($request->filled('section')) {
            $query->where('section_id', $request->section);
        }

        $admins = $query->latest()->get();
        $sections = Section::orderBy('section_name')->get();

        return view('superadmin.index', compact('admins', 'sections'));
    }


    /**
     * Store a new admin user (user_type = 1).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'position'   => 'nullable|string|max:255',
            'section_id' => 'required|exists:sections,id',
        ]);

        User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'position'   => $validated['position'] ?? null,
            'section_id' => $validated['section_id'],
            'user_type'  => User::TYPE_ADMIN, // constant for clarity
        ]);

        return redirect()->route('superadmin.index')->with('success', 'Admin user added successfully.');
    }
}
