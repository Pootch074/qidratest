<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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

    public function post(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 1;
        $validated['created_at'] = $validated['updated_at'] = Carbon::now();

        $user = User::create($validated);

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

    public function put($id, UpdateUserRequest $request)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validated();
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
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
