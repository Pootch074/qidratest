<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

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

        $steps = DB::table('steps')
            ->leftJoin('windows', 'steps.id', '=', 'windows.step_id')
            ->leftJoin('transactions', 'windows.id', '=', 'transactions.window_id') // 👈 add transactions
            ->where('steps.section_id', $user->section_id)
            ->select(
                'steps.id as step_id',
                'steps.step_number',
                'steps.step_name',
                'windows.id as window_id',
                'windows.window_number',
                'transactions.id as tx_id',
                'transactions.queue_number',
                'transactions.client_type'
            )
            ->orderBy('steps.step_number')
            ->orderBy('windows.window_number')
            ->orderBy('transactions.queue_number')
            ->get()
            ->groupBy('step_id')
            ->map(function ($group) {
                return [
                    'step_number' => $group->first()->step_number,
                    'step_name'   => $group->first()->step_name,
                    'windows'     => $group->groupBy('window_id')->map(function ($wins) {
                        return [
                            'window_id'     => $wins->first()->window_id,
                            'window_number' => $wins->first()->window_number,
                            'transactions'  => $wins->filter(fn($t) => $t->tx_id !== null)->map(function ($t) {
                                // 👇 Format queue number
                                $prefix = strtoupper(substr($t->client_type, 0, 1)); 
                                $formatted = $prefix . str_pad($t->queue_number, 3, '0', STR_PAD_LEFT);
                                return [
                                    'id'            => $t->tx_id,
                                    'queue_number'  => $formatted,
                                    'client_type'   => $t->client_type,
                                ];
                            })->values()
                        ];
                    })->filter(fn($w) => $w['window_id'] !== null)->values()
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



// App\Http\Controllers\DisplayController.php
public function getLatestTransaction()
{
    $tx = Transaction::with(['step', 'window'])
        ->where('queue_status', 'serving')
        ->latest('updated_at')
        ->first();

    if (!$tx) {
        return response()->json([]);
    }

    return response()->json([
        'id'            => $tx->id,
        'queue_number'  => $tx->queue_number,
        'client_type'   => $tx->client_type,
        'step_number'   => $tx->step->step_number ?? null,
        'window_number' => $tx->window->window_number ?? null,
    ]);
}






}
