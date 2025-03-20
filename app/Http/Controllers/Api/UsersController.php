<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LguUser;
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
            $lgu = LguUser::where('user_id', $user->id)->first();

            return [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'user_type' => $user->getUserType($user->user_type),
                'position' => $user->position,
                'lgu' => $lgu->lgu->name ?? 'N/A',
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
                'lgu'           => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validate['created_at'] = $validate['updated_at'] = Carbon::now();
        $validate['password'] = '-';
        $validate['status'] = 1;
        $user = User::create($validate);

        // assign user to lgu
        $lguName = 'N/A';
        if ($validate['lgu']) {
            $lguUser = new LguUser([
                'user_id' => $user->id,
                'lgu_id' => $validate['lgu']
            ]);
            $lguUser->save();
            $lguName = $lguUser->lgu->name;
        }

        return response()->json(['message' => 'User added successfully!', 'user' => [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
            'user_type' => $user->getUserType($user->user_type),
            'position' => $user->position,
            'lgu' => $lguName,
            'status' => $user->getStatus($user->status)
            ]], 201);
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

    public function register(Request $request)
    {
        if (!isset($request->email)) {
            return response()->json(['message' => 'Email not found'], 404);
        }
        $user = new User([
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => User::TYPE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'position' => 'QA',
        ]);
        $user->save();

        return json_encode($user);
    }
}
