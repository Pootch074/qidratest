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
    //
    public function get()
    {
        return response()->json(User::all()->map(function ($user) {
            $lguId = 0;
            $lguName = 'N/A';

            if ($user->user_type == User::TYPE_LGU) {
                $lgu = LguUser::where('user_id', $user->id)->first();
                $lguId = $lgu->lgu_id;
                $lguName = $lgu->lgu->name;
            }

            return [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_type' => $user->getUserType($user->user_type),
                'user_type_id' => $user->user_type,
                'position' => $user->position,
                'lgu' => $lguName,
                'lgu_id' => $lguId,
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
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        $existing = \App\Models\User::withTrashed()->where('email', $value)->first();
                        if ($existing && !$existing->trashed()) {
                            $fail('The email has already been taken.');
                        }
                    },
                ],
                'password'      => 'required|string|min:6',
                'position'      => 'nullable|string',
                'user_type'     => 'nullable|integer|in:1,2,3,4',
                'lgu'           => 'nullable|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validate['created_at'] = $validate['updated_at'] = Carbon::now();
        $validate['password'] = Hash::make($request->password);
        $validate['status'] = 1;

        $existingDeletedUser = User::withTrashed()->where('email', $request->email)->first();
        if ($existingDeletedUser && $existingDeletedUser->trashed()) {
            $existingDeletedUser->restore();
            $existingDeletedUser->update($validate);
            $user = $existingDeletedUser;
        } else {
            $user = User::create($validate);
        }

        // assign user to lgu
        $lguName = 'N/A';
        $lguId = 0;
        if ($validate['user_type'] == User::TYPE_LGU && $validate['lgu']) {
            $lguUser = new LguUser([
                'user_id' => $user->id,
                'lgu_id' => $validate['lgu']
            ]);
            $lguUser->save();
            $lguId = $lguUser->lgu_id;
            $lguName = $lguUser->lgu->name;
        }

        return response()->json(['message' => 'User added successfully!', 'user' => [
            'id' => $user->id,
            'email' => $user->email,
            'password' => Hash::make($user->password),
            'name' => $user->first_name . ' ' . $user->last_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'user_type' => $user->getUserType($user->user_type),
            'user_type_id' => $user->user_type,
            'position' => $user->position,
            'lgu' => $lguName,
            'lgu_id' => $lguId,
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
                'password'    => 'nullable|string|min:6',
                'user_type'   => 'nullable|integer|in:1,2,3,4',
                'position'    => 'nullable|string',
                'lgu'         => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Update user details
        $validatedData['updated_at'] = Carbon::now();
        // Preserve the existing password if a new one is not provided
        if (!$request->filled('password')) {
            $validatedData['password'] = $user->password;
        } else {
            $validatedData['password'] = Hash::make($request->password);
        }
        $user->update($validatedData);

        // assign user to lgu
        $lguName = 'N/A';
        $lguId = 0;
        if ($validatedData['user_type'] == User::TYPE_LGU && $validatedData['lgu']) {
            $lguUser = new LguUser([
                'user_id' => $id,
                'lgu_id' => $validatedData['lgu']
            ]);
            $lguUser->save();
            $lguId = $lguUser->lgu_id;
            $lguName = $lguUser->lgu->name;
        }

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => [
                'id' => $id,
                'email' => $validatedData['email'],
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'user_type' => $user->getUserType($validatedData['user_type']),
                'user_type_id' => $user->user_type,
                'position' => $validatedData['position'],
                'lgu' => $lguName,
                'lgu_id' => $lguId,
                'status' => $user->getStatus($user['status'])
            ]
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
