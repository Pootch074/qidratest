<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Window;
use App\Models\Step;
use Illuminate\Support\Facades\Auth;

class WindowsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch steps belonging to the logged-in user's section
        $steps = Step::where('section_id', $user->section_id)
            ->orderBy('step_number')
            ->get();

        // Fetch windows only for steps in user's section
        $windows = Window::with('step')
            ->whereHas('step', function ($query) use ($user) {
                $query->where('section_id', $user->section_id);
            })
            ->orderBy('step_id', 'asc')
            ->get();

        return view('admin.windows.table', compact('steps', 'windows'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'step_id' => 'required|exists:steps,id',
            'window_number' => 'required|integer|min:1|max:10',
        ]);

        // Ensure step belongs to user's section
        $step = Step::where('id', $request->step_id)
            ->where('section_id', $user->section_id)
            ->firstOrFail();

        // Check if window number already exists for this step
        $exists = Window::where('step_id', $step->id)
            ->where('window_number', $request->window_number)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['window_number' => 'This window number already exists for the selected step.'])
                ->withInput();
        }

        // Create the window
        Window::create([
            'step_id' => $step->id,
            'window_number' => $request->window_number,
        ]);

        return redirect()->back()->with('success', 'Window created successfully!');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        // Find the window, ensuring it belongs to a step in user's section
        $window = Window::where('id', $id)
            ->whereHas('step', function ($query) use ($user) {
                $query->where('section_id', $user->section_id);
            })
            ->firstOrFail();

        $window->delete();

        return response()->json([
            'success' => true,
            'message' => 'Window deleted successfully.'
        ]);
    }

    public function check($stepId, $windowNumber)
    {
        // Defensive: validate inputs
        if (!is_numeric($windowNumber) || $windowNumber < 1 || $windowNumber > 10) {
            return response()->json(['exists' => false]);
        }

        $exists = Window::where('step_id', $stepId)
            ->where('window_number', $windowNumber)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    // 🚫 Block updates to Step or Window
    public function update(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Updating windows or steps is not allowed.'
        ], 405);
    }
}
