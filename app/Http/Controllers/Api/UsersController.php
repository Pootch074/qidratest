<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LguUser;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function get()
    {
        // Get all users, map their details, and return as JSON
        return response()->json(User::all()->map(function ($user) {


            // Return structured user data
            return [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_type' => $user->getUserType($user->user_type), // assuming you have this method in your User model
                'user_type_id' => $user->user_type,
                'position' => $user->position,
                'assigned_category' => $user->assigned_category,
                'window_id' => $user->window_id,
                'status' => $user->getStatus($user->status), // assuming you have this method in your User model
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        }));
    }

    // Create a new user
    public function post(Request $request)
    {
        try {
            // Validate request data
            $validate = $request->validate([
                'first_name'       => 'required|string',
                'last_name'        => 'required|string',
                'email'            => 'required|email|unique:users,email',
                'password'         => 'required|string|min:6',
                'position'         => 'nullable|string',
                'user_type'        => 'required|integer|in:0,1,2,3,4',
                'assigned_category' => 'nullable|in:regular,priority',
                'window_id'        => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Encrypt password
        $validate['password'] = Hash::make($request->password);
        $validate['status']   = 1; // default active
        $validate['created_at'] = $validate['updated_at'] = Carbon::now();

        // Create user
        $user = User::create($validate);

        // Return success response
        return response()->json([
            'message' => 'User added successfully!',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_type' => $user->getUserType($user->user_type),
                'user_type_id' => $user->user_type,
                'position' => $user->position,
                'assigned_category' => $user->assigned_category,
                'window_id' => $user->window_id,
                'status' => $user->getStatus($user->status),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 201);
    }


    // Update user
    public function put($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        try {
            $validatedData = $request->validate([
                'first_name'       => 'required|string',
                'last_name'        => 'required|string',
                'email'            => "required|email|unique:users,email,{$id}",
                'password'         => 'nullable|string|min:6',
                'position'         => 'nullable|string',
                'user_type'        => 'required|integer|in:0,1,2,3,4',
                'assigned_category' => 'nullable|in:regular,priority',
                'window_id'        => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validatedData['updated_at'] = Carbon::now();

        // Keep old password if none provided
        if (!$request->filled('password')) {
            $validatedData['password'] = $user->password;
        } else {
            $validatedData['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_type' => $user->getUserType($user->user_type),
                'user_type_id' => $user->user_type,
                'position' => $user->position,
                'assigned_category' => $user->assigned_category,
                'window_id' => $user->window_id,
                'status' => $user->getStatus($user->status),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }


    // Delete user
    public function delete($id)
    {
        // Find user by ID
        $user = User::find($id);

        // If not found return 404
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Soft delete the user
        $user->delete();

        // Return success response
        return response()->json(['message' => 'User deleted successfully']);
    }

    // Register user (simplified for initial setup)
    public function register(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'first_name'       => 'required|string',
                'last_name'        => 'required|string',
                'email'            => 'required|email|unique:users,email',
                'password'         => 'required|string|min:6',
                'position'         => 'nullable|string',
                'user_type'        => 'required|integer|in:0,1,2,3,4',
                'assigned_category' => 'nullable|in:regular,priority',
                'window_id'        => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Encrypt password
        $validatedData['password'] = Hash::make($request->password);
        $validatedData['status']   = 1; // active by default
        $validatedData['created_at'] = $validatedData['updated_at'] = Carbon::now();

        // Create new user
        $user = User::create($validatedData);

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_type' => $user->getUserType($user->user_type),
                'user_type_id' => $user->user_type,
                'position' => $user->position,
                'assigned_category' => $user->assigned_category,
                'window_id' => $user->window_id,
                'status' => $user->getStatus($user->status),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 201);
    }
}
