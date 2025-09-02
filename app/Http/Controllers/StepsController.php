<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Step;
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
            'step_number' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('steps')->where(function ($query) {
                    return $query->where('section_id', Auth::user()->section_id);
                }),
            ],
        ]);

        Step::create([
            'step_number' => $request->step_number,
            'step_name' => $request->step_name ?: 'None',
            'section_id' => Auth::user()->section_id,
        ]);

        return redirect()->route('admin.steps')->with('success', 'Step added successfully!');
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
