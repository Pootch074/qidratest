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

    // queue numbers are organized per window
    public function getStepsBySectionId()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // apply category filtering only when user is in section 15
            $applyCategoryFilter = $user->section_id === 15;

            $steps = DB::table('steps')
                ->leftJoin('windows', 'steps.id', '=', 'windows.step_id')
                ->leftJoin('transactions', function ($join) use ($user, $applyCategoryFilter) {
                    // base ON conditions
                    $join->on('windows.id', '=', 'transactions.window_id')
                        ->where('transactions.queue_status', '=', 'serving')
                        ->whereDate('transactions.created_at', now());

                    if ($applyCategoryFilter) {
                        // For section 15: join transactions if EITHER
                        //  - step_number IN (1,2) AND client_type = assigned_category
                        //  - OR step_number NOT IN (1,2) (no client_type restriction)
                        $join->where(function ($q) use ($user) {
                            $q->where(function ($q2) use ($user) {
                                $q2->whereIn('steps.step_number', [1, 2])
                                    ->where('transactions.client_type', '=', $user->assigned_category);
                            })->orWhereNotIn('steps.step_number', [1, 2]);
                        });
                    }
                })
                ->where('steps.section_id', $user->section_id) // always restrict to user's section
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
                            if (!$wins->first()->window_id) return null;

                            return [
                                'window_id'     => $wins->first()->window_id,
                                'window_number' => $wins->first()->window_number,
                                'transactions'  => $wins->filter(fn($t) => $t->tx_id !== null)->map(function ($t) {
                                    $prefix = strtoupper(substr($t->client_type, 0, 1));
                                    $formatted = $prefix . str_pad($t->queue_number, 3, '0', STR_PAD_LEFT);

                                    return [
                                        'id'           => $t->tx_id,
                                        'queue_number' => $formatted,
                                        'client_type'  => $t->client_type,
                                    ];
                                })->values()
                            ];
                        })->filter(fn($w) => $w !== null)->values()
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
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Base query: only serving + today's transactions and must belong to user's section
        $query = Transaction::with(['step', 'window'])
            ->where('queue_status', 'serving')
            ->whereDate('created_at', now())
            ->whereHas('step', function ($q) use ($user) {
                $q->where('section_id', $user->section_id);
            });

        // Only apply the conditional client_type rule when user is in section 15
        if ($user->section_id === 15) {
            $query->where(function ($q) use ($user) {
                // Either:
                //  - transaction is in step 1 or 2 AND client_type matches assigned_category
                //  - OR transaction is in any other step (no client_type restriction)
                $q->where(function ($q2) use ($user) {
                    $q2->whereHas('step', function ($s) {
                        $s->whereIn('step_number', [1, 2]);
                    })->where('client_type', $user->assigned_category);
                })->orWhereHas('step', function ($s) {
                    $s->whereNotIn('step_number', [1, 2]);
                });
            });
        }

        $txs = $query->orderBy('updated_at', 'desc')->get();

        if ($txs->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($txs->map(function ($tx) {
            return [
                'id'            => $tx->id,
                'queue_number'  => $tx->queue_number,
                'client_type'   => $tx->client_type,
                'step_number'   => $tx->step->step_number ?? null,
                'window_number' => $tx->window->window_number ?? null,
                'recall_count'  => $tx->recall_count ?? 0,
            ];
        }));
    }
}
