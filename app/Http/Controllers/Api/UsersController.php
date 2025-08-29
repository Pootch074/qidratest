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
            // Initialize default LGU values
            $lguId = 0;
            $lguName = 'N/A';

            // If the user is an LGU user, fetch LGU details
            if ($user->user_type == User::TYPE_LGU) {
                $lgu = LguUser::where('user_id', $user->id)->first();
                $lguId = $lgu->lgu_id;
                $lguName = $lgu->lgu->name;
            }

            // Return structured user data
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

    // Create a new user
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
                        // Check if email already exists (including soft-deleted users)
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
            // Return validation errors with 422 status code
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Set created_at and updated_at timestamps
        $validate['created_at'] = $validate['updated_at'] = Carbon::now();

        // Encrypt password
        $validate['password'] = Hash::make($request->password);

        // Default status as active
        $validate['status'] = 1;

        // Check if user already exists but was soft-deleted
        $existingDeletedUser = User::withTrashed()->where('email', $request->email)->first();
        if ($existingDeletedUser && $existingDeletedUser->trashed()) {
            // Restore the user and update details
            $existingDeletedUser->restore();
            $existingDeletedUser->update($validate);
            $user = $existingDeletedUser;
        } else {
            // Otherwise create a new user
            $user = User::create($validate);
        }

        // Assign user to LGU if applicable
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

        // Return success response with user data
        return response()->json(['message' => 'User added successfully!', 'user' => [
            'id' => $user->id,
            'email' => $user->email,
            // NOTE: Rehashing the password here is redundant; it should not be returned
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

    // Update user
    public function put($id, Request $request)
    {
        // Find the user by ID
        $user = User::find($id);

        // If user not found return 404
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
            // Return validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Update timestamp
        $validatedData['updated_at'] = Carbon::now();

        // If password not provided, keep existing one
        if (!$request->filled('password')) {
            $validatedData['password'] = $user->password;
        } else {
            $validatedData['password'] = Hash::make($request->password);
        }

        // Update user with validated data
        $user->update($validatedData);

        // Assign LGU if applicable
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

        // Return updated user response
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
        // Ensure email exists in request
        if (!isset($request->email)) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Create new user with default values
        $user = new User([
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => User::TYPE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'position' => 'QA',
        ]);

        // Save user in database
        $user->save();

        // Return user as JSON
        return json_encode($user);
    }
}
