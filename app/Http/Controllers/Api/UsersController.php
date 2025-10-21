<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function get()
    {
        return response()->json(User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name.' '.$user->last_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_type' => $user->getUserType($user->user_type),
                'user_type_id' => $user->user_type,
                'position' => $user->position,
                'assigned_category' => $user->assigned_category,
                'window_id' => $user->window_id,
                'status' => $user->getStatus($user->status),
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        }));
    }

    public function post(Request $request)
    {
        try {
            $validate = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'position' => 'nullable|string',
                'user_type' => 'required|integer|in:0,1,2,3,4',
                'assigned_category' => 'nullable|in:regular,priority',
                'window_id' => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validate['password'] = Hash::make($request->password);
        $validate['status'] = 1;
        $validate['created_at'] = $validate['updated_at'] = Carbon::now();

        $user = User::create($validate);

        return response()->json([
            'message' => 'User added successfully!',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name.' '.$user->last_name,
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
            ],
        ], 201);
    }

    public function put($id, Request $request)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => "required|email|unique:users,email,{$id}",
                'password' => 'nullable|string|min:6',
                'position' => 'nullable|string',
                'user_type' => 'required|integer|in:0,1,2,3,4',
                'assigned_category' => 'nullable|in:regular,priority',
                'window_id' => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validatedData['updated_at'] = Carbon::now();

        if (! $request->filled('password')) {
            $validatedData['password'] = $user->password;
        } else {
            $validatedData['password'] = Hash::make($request->password);
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name.' '.$user->last_name,
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
            ],
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'position' => 'nullable|string',
                'user_type' => 'required|integer|in:0,1,2,3,4',
                'assigned_category' => 'nullable|in:regular,priority',
                'window_id' => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validatedData['password'] = Hash::make($request->password);
        $validatedData['status'] = 1;
        $validatedData['created_at'] = $validatedData['updated_at'] = Carbon::now();

        $user = User::create($validatedData);

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name.' '.$user->last_name,
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
            ],
        ], 201);
    }
}
