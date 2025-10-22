<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Step;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PacdController extends Controller
{
    protected $excludedSectionNames = [
        'ACCOUNTING SECTION',
        'PROPERTY AND SUPPLY SECTION',
        'RECORDS AND ARCHIVE MANAGEMENT SECTION',
        'HR PERSONNEL ADMINISTRATION SECTION (HRPASS)',
        'LEGAL UNIT',
        'SOCIAL MARKETING UNIT',
        'SOCIAL TECHNOLOGY UNIT',
        'POLICY DEVELOPMENT AND PLANNING SECTION',
        'STANDARDS SECTION',
        'CRISIS INTERVENTION SECTION',
    ];

    protected function getExcludedSectionIds()
    {
        return Cache::remember('excluded_section_ids', 300, function () {
            return Section::whereIn('section_name', $this->excludedSectionNames)->pluck('id');
        });
    }

    public function index()
    {
        $user = Auth::user();
        $clients = Transaction::whereNull('ticket_status')
            ->orderBy('id')
            ->get(['id', 'full_name', 'created_at']);

        $excludedSectionIds = $this->getExcludedSectionIds();

        // Sections for buttons
        if (is_null($user->section_id)) {
            $sections = Section::whereNotIn('id', $excludedSectionIds)
                ->orderBy('section_name')
                ->get(['id', 'section_name']);
        } else {
            $sections = Section::where('id', $user->section_id)
                ->orderBy('section_name')
                ->get(['id', 'section_name']);
        }

        return view('pacd.index', compact('sections', 'clients'));
    }

    public function generateQueue(Request $request, Section $section)
    {
        $clientType = $request->input('client_type', 'regular');
        $clientId = $request->input('client_id');
        $clientName = $request->input('manual_client_name');

        if ($clientId) {
            $client = Transaction::where('id', $clientId)
                ->whereNull('ticket_status')
                ->firstOrFail();
        } else {
            $client = new Transaction([
                'full_name' => $clientName,
                'ticket_status' => null,
            ]);
        }

        $today = Carbon::today();

        $lastQueue = Transaction::where('section_id', $section->id)
            ->where('client_type', $clientType)
            ->whereDate('created_at', $today)
            ->max('queue_number');

        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        $firstStep = Step::where('section_id', $section->id)
            ->where('step_number', 1)
            ->first();

        $client->fill([
            'queue_number' => $newQueueNumber,
            'client_type' => $clientType,
            'step_id' => $firstStep?->id,
            'window_id' => null,
            'section_id' => $section->id,
            'queue_status' => 'waiting',
            'ticket_status' => 'issued',
        ])->save();

        $prefixMap = [
            'priority' => 'P',
            'regular' => 'R',
            'returnee' => 'T',
            'deferred' => 'D',
        ];

        $prefix = $prefixMap[$clientType] ?? strtoupper(substr($clientType, 0, 1));
        $formattedQueue = $prefix . str_pad($client->queue_number, 3, '0', STR_PAD_LEFT);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'queue_number' => $formattedQueue,
                'client_type' => ucfirst($clientType),
                'client_name' => $client->full_name,
                'section' => $section->section_name,
            ]);
        }

        return redirect()->back()
            ->with('success', "Queue #{$formattedQueue} created for {$section->section_name} (Client: {$client->full_name})");
    }

    public function transactionsTable()
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Manila');
        $excludedSectionIds = $this->getExcludedSectionIds();

        $query = Transaction::with(['step', 'section'])
            ->where('queue_number', '>', 0)
            ->whereDate('created_at', $today)
            ->whereDate('updated_at', $today)
            ->orderBy('queue_number', 'desc')
            ->latest();

        if (is_null($user->section_id)) {
            $transactions = $query->whereNotIn('section_id', $excludedSectionIds)->get();
        } else {
            $transactions = $query->where('section_id', $user->section_id)->get();
        }

        return view('pacd.transactions.table', compact('transactions'));
    }

    public function sectionsCards()
    {
        $user = Auth::user();
        $excludedSectionIds = $this->getExcludedSectionIds();

        if (is_null($user->section_id)) {
            $sections = Section::whereNotIn('id', $excludedSectionIds)
                ->orderBy('section_name')
                ->get(['id', 'section_name']);
        } else {
            $sections = Section::where('id', $user->section_id)
                ->orderBy('section_name')
                ->get(['id', 'section_name']);
        }

        return view('pacd.sections.cards', compact('sections'));
    }

     public function pendingQueues()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        $pendingQueues = Transaction::where('queue_status', 'deferred')
            ->where('ticket_status', 'issued')
            ->whereDate('created_at', $yesterday)
            ->get();

        return view('pacd.pending.table', compact('pendingQueues'));
    }

    public function resumeTransaction(Request $request, $id)
    {
        $now = Carbon::now('Asia/Manila');
        $oldTransaction = Transaction::findOrFail($id);

        $oldTransaction->update([
            'ticket_status' => 'cancelled',
            'updated_at' => $now,
        ]);

        $today = Carbon::today('Asia/Manila')->toDateString();
        $lastQueue = Transaction::where('client_type', 'deferred')
            ->where('section_id', $oldTransaction->section_id)
            ->whereDate('created_at', $today)
            ->max('queue_number');

        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        $transaction = Transaction::create([
            'full_name' => $request->full_name,
            'client_type' => 'deferred',
            'queue_number' => $newQueueNumber,
            'queue_status' => 'waiting',
            'ticket_status' => 'issued',
            'step_id' => $oldTransaction->step_id,
            'window_id' => null,
            'section_id' => $oldTransaction->section_id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $formattedQueue = 'D' . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'queue_number' => $formattedQueue,
            'full_name' => $transaction->full_name,
            'section' => $transaction->section->section_name ?? '',
            'step_number' => $transaction->step->step_number ?? '',
            'client_type' => ucfirst($transaction->client_type),
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    public function clientsTable()
    {
        $user = Auth::user();

        $query = Transaction::whereNull('ticket_status')->orderBy('id');

        if (is_null($user->section_id)) {
            $query->whereNull('section_id');
        } else {
            $query->where('section_id', $user->section_id);
        }

        $clients = $query->get(['id', 'full_name', 'created_at']);

        return view('pacd.scanned_id.table', compact('clients'));
    }
}
