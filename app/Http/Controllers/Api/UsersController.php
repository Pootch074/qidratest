<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    //
    public function get()
    {
        return response()->json(User::all()->map(function ($user) {
            $lgu = 'N/A';
            return [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'user_type' => $user->getUserType($user->user_type),
                'position' => $user->position,
                'lgu' => $lgu,
                'status' => $user->getStatus($user->status)
            ];
        }));
    }

    public function post(Request $request)
    {
        try {
            // Validate request data
            $validate = $request->validate([
                'first_name'    => 'required|string',
                'last_name'     => 'nullable|string',
                'email'         => 'required|email|unique:users,email',
                'position'      => 'nullable|string',
                'user_type'     => 'nullable|integer|in:1,2,3,4',
                'status'        => 'nullable|integer|in:0,1',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validatedData['created_at'] = $validatedData['updated_at'] = Carbon::now();
        $validatedData['password'] = '-';
        $user = User::create($validatedData);

        return response()->json(['message' => 'User added successfully!', 'user' => $user], 201);
    }

    public function put($id, Request $request)
    {

        $user = User::find($id); // Find the user

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        try {
            // Validate request data
            $validatedData = $request->validate([
                'first_name'  => 'required|string',
                'last_name'   => 'nullable|string',
                'email'       => "required|email|unique:users,email,{$id}",
                'user_type'   => 'nullable|integer|in:1,2,3,4',
                'position'    => 'nullable|string',
                'lgu'         => 'nullable|string',
                'status'      => 'nullable|integer|in:0,1',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Update user details
        $validatedData['updated_at'] = Carbon::now();
        $updated = $user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user // Return the updated user
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id); // Find the user by ID

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete(); // Delete the user

        return response()->json(['message' => 'User deleted successfully']);
    }
}
