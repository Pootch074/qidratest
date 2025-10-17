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
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = ClientLogs::orderBy('id', 'desc');
        $total = $query->count();

        if ($perPage === 'all') {
            $clients = $query->paginate($total)->appends($request->all());
        } else {
            $clients = $query->paginate((int)$perPage)->appends($request->all());
        }

        return view('idscan.index', compact('clients'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'id_image' => 'required|image|mimes:jpg,jpeg,png|max:204800',
        ]);
        
        $originalName = $request->file('id_image')->getClientOriginalName();
        $filename = time() . '_' . $originalName;

        // Save to public/images/uploads
        $path = $request->file('id_image')->storeAs('uploads', $filename, 'public');

        // $path = $request->file('id_image')->store('uploads', 'public');

        $response = $this->ocr_process($filename, $path);

        // Redirect back with a success message
        return redirect()->back()->with('name', $response);
    }

    function ocr_process($filename, $filepath) {
        $python = base_path('venv/Scripts/python.exe');

        $script_path = base_path('storage/scripts/easy_ocr.py');

        $args = array_map(fn($arg) => trim($arg, "\""), [
                $python,
                $script_path,
                $filepath,
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
            return 'Something went wrong.';
        }

        $data = json_decode($output, true);

        // dd($data);   

        if (isset($data['status']) && $data['status'] == 'success'){
            // dd($data);
            // $this->saveName($data['name']);
            $result = [
                'name' => $data['result'],
                'image' => $filename,
            ];
            return $result;
        } elseif (isset($data['status']) && $data['status'] == 'error'){
            // dd($data);
            return $data['message'];
        }

        if (!$data || $data === null) {
            Log::error("Invalid JSON response from Python script: " . $output);
            // return back()->with('error', 'Invalid response from the Python script.');
            return 'Invalid response from the Python script.';
        }
    
        if (isset($data['status']) && $data['status'] == 'error'){
            // dd($data);
            return $data['message'];
        }
    }

    public function saveName(Request $request)
    {
        $request->validate([
            'confirmed_name' => 'required|string|max:255',
            'image' => 'required|string|max:255',
        ]);

        $name = $request->input('confirmed_name');
        $image = $request->input('image');

        // Or using DB facade directly
        DB::table('client_logs')->insert([
            'client_name' => $name,
            'image' => $image,
        ]);

        return redirect()->back()->with('success','Saved successfully.');
    }
}
