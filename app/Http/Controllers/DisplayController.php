<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Step;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisplayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.display.index');
    }

    public function getStepsBySectionId()
    {
        try {
            $user = Auth::user();
            $frontDeskSectionId = Section::where('section_name', 'CRISIS INTERVENTION SECTION')->value('id');
            $initialStepIds = Step::whereIn('step_number', [1, 2])->pluck('id')->toArray();

            $applyCategoryFilter = $user->section_id === $frontDeskSectionId;

            $steps = DB::table('steps')
                ->leftJoin('windows', 'steps.id', '=', 'windows.step_id')
                ->leftJoin('transactions', function ($join) use ($user, $applyCategoryFilter, $initialStepIds) {
                    $join->on('windows.id', '=', 'transactions.window_id')
                        ->where('transactions.queue_status', '=', 'serving')
                        ->whereDate('transactions.created_at', now());

                    if ($applyCategoryFilter) {
                        $join->where(function ($q) use ($user, $initialStepIds) {
                            $q->where(function ($q2) use ($user, $initialStepIds) {
                                $q2->whereIn('steps.id', $initialStepIds)
                                    ->where(function ($sub) use ($user) {
                                        $sub->where('transactions.client_type', $user->assigned_category)
                                            ->orWhere('transactions.client_type', 'deferred');
                                    });
                            })
                                ->orWhereNotIn('steps.id', $initialStepIds);
                        });
                    }
                })
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
                    $firstStep = $group->first();

                    return [
                        'step_number' => $firstStep->step_number,
                        'step_name' => $firstStep->step_name,
                        'windows' => $group->groupBy('window_id')->map(function ($wins) {
                            $firstWindow = $wins->first();

                            if (! $firstWindow->window_id) {
                                return null;
                            }

                            return [
                                'window_id' => $firstWindow->window_id,
                                'window_number' => $firstWindow->window_number,
                                'transactions' => $wins
                                    ->filter(fn ($t) => $t->tx_id !== null)
                                    ->map(function ($t) {
                                        $prefix = strtoupper(substr($t->client_type, 0, 1));
                                        $formatted = $prefix.str_pad($t->queue_number, 3, '0', STR_PAD_LEFT);

                                        return [
                                            'id' => $t->tx_id,
                                            'queue_number' => $formatted,
                                            'client_type' => $t->client_type,
                                        ];
                                    })
                                    ->values(),
                            ];
                        })->filter(fn ($w) => $w !== null)->values(),
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

    public function getLatestTransaction()
    {
        $user = Auth::user();

        $frontDeskSectionId = Section::where('section_name', 'CRISIS INTERVENTION SECTION')->value('id');
        $initialStepIds = Step::whereIn('step_number', [1, 2])->pluck('id')->toArray();

        $query = Transaction::with(['step', 'window'])
            ->where('queue_status', 'serving')
            ->whereDate('created_at', now())
            ->whereHas('step', function ($q) use ($user) {
                $q->where('section_id', $user->section_id);
            });

        if ($user->section_id === $frontDeskSectionId) {
            $query->where(function ($q) use ($user, $initialStepIds) {
                $q->where(function ($q2) use ($user, $initialStepIds) {
                    $q2->whereHas('step', function ($s) use ($initialStepIds) {
                        $s->whereIn('id', $initialStepIds);
                    })->where(function ($sub) use ($user) {
                        $sub->where('client_type', $user->assigned_category)
                            ->orWhere('client_type', 'deferred');
                    });
                })->orWhereHas('step', function ($s) use ($initialStepIds) {
                    $s->whereNotIn('id', $initialStepIds);
                });
            });
        }

        $txs = $query->orderBy('updated_at', 'desc')->get();

        if ($txs->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($txs->map(function ($tx) {
            return [
                'id' => $tx->id,
                'queue_number' => $tx->queue_number,
                'client_type' => $tx->client_type,
                'step_number' => $tx->step->step_number ?? null,
                'window_number' => $tx->window->window_number ?? null,
                'recall_count' => $tx->recall_count ?? 0,
            ];
        }));
    }
}
