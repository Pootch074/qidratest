<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ImageCaptureController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'capturedImageData' => 'required|string',
        ]);
        // Get the base64 image data
        $imageData = $request->input('capturedImageData');
        // Remove the "data:image/png;base64," part if it exists
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif
            $base64Image = $imageData;

            // Decode the image data
            $imageData = base64_decode($imageData);

            // $response = $this->callApiV3($base64Image);
            
            if ($imageData === false) {
                return response()->json(['error' => 'Base64 decode failed'], 400);
            }
        } else {
            return response()->json(['error' => 'Invalid image data'], 400);
        }
        // Generate a unique filename
        $filename = 'captured_image_' . time() . '.' . $type;
        // Store the image in the public storage
        Storage::disk('public')->put($filename, $imageData);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Image uploaded successfully');
    }
}
