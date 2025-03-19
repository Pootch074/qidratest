<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lgu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LguController extends Controller
{
    public function get()
    {
        return response()->json(Lgu::all()->map(function ($lgu) {
            return [
                'id' => $lgu->id,
                'name' => $lgu->name,
                'province_id' => $lgu->province_id,
                'province' => $lgu->province->name,
                'region_id' => $lgu->region_id,
                'region' => $lgu->region->name,
                'lgu_type' => $lgu->getLguType($lgu->lgu_type),
                'office_address' => $lgu->office_address,
                'telephone' => $lgu->telephone,
                'mobile_number' => $lgu->mobile_number,
                'email_address' => $lgu->email_address
            ];
        }));
    }

    public function post(Request $request)
    {
        try {
            // Validate request data
            $validate = $request->validate([
                'name' => 'required|string',
                'province_id' => 'required',
                'region_id' => 'required',
                'lgu_type' => 'required|string',
                'office_address' => 'nullable|string',
                'telephone' => 'nullable|string',
                'mobile_number' => 'nullable|string',
                'email_address' => 'nullable|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validatedData['created_at'] = $validatedData['updated_at'] = Carbon::now();
        $lgu = Lgu::create($validatedData);

        return response()->json(['message' => 'Profile added successfully!', 'lgu' => $lgu], 201);
    }

    public function put($id, Request $request)
    {

        $lgu = Lgu::find($id); // Find the profile

        if (!$lgu) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        try {
            // Validate request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'province_id' => 'required',
                'region_id' => 'required',
                'lgu_type' => 'required|string',
                'office_address' => 'nullable|string',
                'telephone' => 'nullable|string',
                'mobile_number' => 'nullable|string',
                'email_address' => 'nullable|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Update profile details
        $validatedData['updated_at'] = Carbon::now();
        $updated = $lgu->update($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully!',
            'lgu' => $updated // Return the updated profile
        ]);
    }

    public function delete($id)
    {
        $lgu = Lgu::find($id); // Find the profile by ID

        if (!$lgu) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $lgu->delete(); // Delete the profile

        return response()->json(['message' => 'Profile deleted successfully']);
    }
}
