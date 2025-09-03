<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisplayController extends Controller
{
    public function index()
    {
        return view('admin.display.index');
    }

public function getStepsBySectionId()
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Join steps with windows
        $steps = DB::table('steps')
            ->leftJoin('windows', 'steps.id', '=', 'windows.step_id')
            ->where('steps.section_id', $user->section_id)
            ->select(
                'steps.id as step_id',
                'steps.step_number',
                'steps.step_name',
                'windows.id as window_id',
                'windows.window_number'
            )
            ->orderBy('steps.step_number')
            ->get()
            ->groupBy('step_id')
            ->map(function ($group) {
                return [
                    'step_number' => $group->first()->step_number,
                    'step_name'   => $group->first()->step_name,
                    'windows'     => $group->map(function ($item) {
                        return [
                            'window_id'     => $item->window_id,
                            'window_number' => $item->window_number
                        ];
                    })->filter(fn ($w) => $w['window_id'] !== null)->values()
                ];
            })
            ->values();

        return response()->json($steps);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage(),
        ], 500);
    }
}



}
