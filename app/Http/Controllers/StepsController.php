<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Step;
use App\Models\Window;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StepsController extends Controller
{
    public function steps()
    {
        $sectionId = Auth::user()->section_id;

        // Get steps for the current user's section
        $steps = Step::where('section_id', $sectionId)
            ->orderBy('step_number', 'asc')
            ->get();
        return view('admin.steps.table', compact('steps'));
    }

   public function store(Request $request)
{
    $request->validate([
        'step_name' => 'required|string|max:255',
    ]);

    $latestStep = Step::where('section_id', Auth::user()->section_id)
        ->max('step_number');

    $nextStepNumber = $latestStep ? $latestStep + 1 : 1;

    // ✅ Create the step and capture it
    $step = Step::create([
        'section_id'   => Auth::user()->section_id,
        'step_number'  => $nextStepNumber,
        'step_name'    => $request->step_name,
    ]);

    // ✅ Automatically create the first window for this step
    Window::create([
        'window_number' => 1,
        'step_id'       => $step->id,
    ]);

    return redirect()->route('admin.steps')->with('success', 'Step and its first window added successfully.');
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'step_name' => 'required|string|max:255',
        ]);

        $step = Step::findOrFail($id);
        $step->step_name = $request->step_name;
        $step->save();

        return response()->json(['success' => true]);
    }
    // app/Http/Controllers/StepController.php
    public function destroy($id)
    {
        try {
            $step = Step::findOrFail($id);
            $step->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete step.'
            ], 500);
        }
    }

    public function check($sectionId, $stepNumber)
    {
        $exists = Step::where('section_id', $sectionId)
            ->where('step_number', $stepNumber)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
