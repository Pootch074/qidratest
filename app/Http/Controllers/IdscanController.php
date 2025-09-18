<?php

namespace App\Http\Controllers;

use App\Models\ClientLogs;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Symfony\Component\Process\Process;

class IdscanController extends Controller
{
    public function index()
    {
        $clients = ClientLogs::latest()->take(10)->get();

        return view('idscan.index', compact('clients'));
    }

    public function uploadImage(Request $request)
    {
        // Validate the incoming request
        // Validate file input
        // dd($request->all(), $request->file('id_image'));

        $request->validate([
            'id_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        

        // Store image in storage/app/public/uploads
        $path = $request->file('id_image')->store('uploads', 'public');
        // dd($path);
        // Remove the "data:image/png;base64," part if it exists
        // if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
        //     $imageData = substr($imageData, strpos($imageData, ',') + 1);
        //     $type = strtolower($type[1]); // jpg, png, gif
        //     $base64Image = $imageData;

        //     // Decode the image data
        //     $imageData = base64_decode($imageData);
            
        //     if ($imageData === false) {
        //         return response()->json(['error' => 'Base64 decode failed'], 400);
        //     }
        // } else {
        //     return response()->json(['error' => 'Invalid image data'], 400);
        // }
        // // Generate a unique filename
        // $filename = 'captured_image_' . time() . '.' . $type;
        // // Store the image in the public storage
        // Storage::disk('public')->put($filename, $imageData);

        $response = $this->ocr_process($path);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Image uploaded successfully');
    }

    function ocr_process($filename) {
        $python = base_path('venv/Scripts/python.exe');

        $script_path = base_path('storage/scripts/easy_ocr.py');

        $args = array_map(fn($arg) => trim($arg, "\""), [
                $python,
                $script_path,
                $filename,
        ]);

        $process = new Process($args);
        $process->setTimeout(null);
        $process->run(); // run() if sync/blocking

        if (!$process->isSuccessful()) {
            Log::error("Error from Python script: " . $process->getErrorOutput());
            throw new \RuntimeException($process->getErrorOutput());
        }

        $output = $process->getOutput();
        // dd($output); 

        if ($output === null) {
            Log::error("Python script execution failed.");
            // return back()->with('error', 'Python script execution failed.');
            return back()->with('error', 'Something went wrong.');
        }

        $data = json_decode($output, true);

        // dd($data);   

        if (isset($data['status']) && $data['status'] == 'success'){
            // dd($data);
            $this->saveName($data['name']);
        } else {
            return back()->with('error', 'Something went wrong.');
        }

        if (!$data || $data === null) {
            Log::error("Invalid JSON response from Python script: " . $output);
            // return back()->with('error', 'Invalid response from the Python script.');
            return back()->with('error', 'Invalid response from the Python script.');
        }
    
        if (isset($data['status']) && $data['status'] == 'error'){
            // dd($data);
            return back()->with('error', $data['message']);
        }
    }

    public function saveName($name)
    {
        DB::table('client_logs')->insert([
            'client_name' => $name,
        ]);

        return true;
    }
}
