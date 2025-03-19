<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rmt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RmtController extends Controller
{
    public function get()
    {
        return response()->json(Rmt::all()->map(function ($rmt) {
            $lgu = 'N/A';
            return [
                'id' => $rmt->id,
                'user_id' => $rmt->user_id,
                'name' => $rmt->user->first_name . ' ' . $rmt->user->last_name,
                'user_type' => $rmt->user->getUserType($rmt->user->user_type),
                'position' => $rmt->user->position,
                'lgu' => $lgu,
                'status' => $rmt->user->getStatus($rmt->user->status)
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
        $rmt->user = User::create($validatedData);

        return response()->json(['message' => 'User added successfully!', 'user' => $rmt->user], 201);
    }

    public function put($id, Request $request)
    {

        $rmt->user = User::find($id); // Find the user

        if (!$rmt->user) {
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
        $updated = $rmt->user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $rmt->user // Return the updated user
        ]);
    }

    public function delete($id)
    {
        $rmt->user = User::find($id); // Find the user by ID

        if (!$rmt->user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $rmt->user->delete(); // Delete the user

        return response()->json(['message' => 'User deleted successfully']);
    }
}
