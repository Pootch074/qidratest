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
        $excludedSectionIds = $this->getExcludedSectionIds();

        // Scanned clients (no ticket yet)
        $clients = Transaction::withoutTicket()
            ->orderBy('id')
            ->get(['id', 'full_name', 'created_at']);

        // Section list depends on user role
        $sections = is_null($user->section_id)
            ? Section::whereNotIn('id', $excludedSectionIds)->orderBy('section_name')->get(['id', 'section_name'])
            : Section::where('id', $user->section_id)->get(['id', 'section_name']);

        return view('pacd.index', compact('sections', 'clients'));
    }

    public function generateQueue(Request $request, Section $section)
    {
        $clientType = $request->input('client_type', 'regular');
        $clientId   = $request->input('client_id');
        $clientName = $request->input('manual_client_name');

        // Existing scanned client or new one
        $client = $clientId
            ? Transaction::withoutTicket()->findOrFail($clientId)
            : new Transaction(['full_name' => $clientName, 'ticket_status' => null]);

        // Compute new queue number
        $today = Carbon::today('Asia/Manila');
        $lastQueue = Transaction::forSection($section->id)
            ->ofClientType($clientType)
            ->today()
            ->max('queue_number');

        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // Get first step dynamically
        $firstStep = Step::where('section_id', $section->id)
            ->where('step_number', 1)
            ->first();

        // Save client queue
        $client->fill([
            'queue_number'  => $newQueueNumber,
            'client_type'   => $clientType,
            'step_id'       => $firstStep?->id,
            'window_id'     => null,
            'section_id'    => $section->id,
            'queue_status'  => 'waiting',
            'ticket_status' => 'issued',
        ])->save();

        $prefixMap = ['priority' => 'P', 'regular' => 'R', 'returnee' => 'T', 'deferred' => 'D'];
        $prefix = $prefixMap[$clientType] ?? strtoupper(substr($clientType, 0, 1));
        $formattedQueue = $prefix . str_pad($client->queue_number, 3, '0', STR_PAD_LEFT);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'       => true,
                'queue_number'  => $formattedQueue,
                'client_type'   => ucfirst($clientType),
                'client_name'   => $client->full_name,
                'section'       => $section->section_name,
            ]);
        }

        return redirect()->back()
            ->with('success', "Queue #{$formattedQueue} created for {$section->section_name} (Client: {$client->full_name})");
    }

    public function transactionsTable()
    {
        $user = Auth::user();
        $excludedSectionIds = $this->getExcludedSectionIds();

        $query = Transaction::with(['step', 'section'])
            ->withQueueNumber()
            ->today()
            ->orderBy('queue_number', 'desc')
            ->latest();

        $transactions = is_null($user->section_id)
            ? $query->excludeSections($excludedSectionIds)->get()
            : $query->forSection($user->section_id)->get();

        return view('pacd.transactions.table', compact('transactions'));
    }

    public function sectionsCards()
    {
        $user = Auth::user();
        $excludedSectionIds = $this->getExcludedSectionIds();

        $sections = is_null($user->section_id)
            ? Section::whereNotIn('id', $excludedSectionIds)->orderBy('section_name')->get(['id', 'section_name'])
            : Section::where('id', $user->section_id)->get(['id', 'section_name']);

        return view('pacd.sections.cards', compact('sections'));
    }


     public function pendingQueues()
    {
        $pendingQueues = Transaction::deferred()
            ->issued()
            ->yesterday()
            ->get();

        return view('pacd.pending.table', compact('pendingQueues'));
    }

    public function resumeTransaction(Request $request, $id)
    {
        $now = Carbon::now('Asia/Manila');
        $oldTransaction = Transaction::findOrFail($id);

        $oldTransaction->update([
            'ticket_status' => 'cancelled',
            'updated_at'    => $now,
        ]);

        $today = Carbon::today('Asia/Manila')->toDateString();

        $lastQueue = Transaction::ofClientType('deferred')
            ->forSection($oldTransaction->section_id)
            ->today()
            ->max('queue_number');

        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        $transaction = Transaction::create([
            'full_name'     => $request->full_name,
            'client_type'   => 'deferred',
            'queue_number'  => $newQueueNumber,
            'queue_status'  => 'waiting',
            'ticket_status' => 'issued',
            'step_id'       => $oldTransaction->step_id,
            'window_id'     => null,
            'section_id'    => $oldTransaction->section_id,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        $formattedQueue = 'D' . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'success'       => true,
            'queue_number'  => $formattedQueue,
            'full_name'     => $transaction->full_name,
            'section'       => $transaction->section->section_name ?? '',
            'step_number'   => $transaction->step->step_number ?? '',
            'client_type'   => ucfirst($transaction->client_type),
            'created_at'    => $transaction->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    public function clientsTable()
    {
        $user = Auth::user();

        $query = Transaction::withoutTicket()->orderBy('id');

        $query = is_null($user->section_id)
            ? $query->whereNull('section_id')
            : $query->forSection($user->section_id);

        $clients = $query->get(['id', 'full_name', 'created_at']);

        return view('pacd.scanned_id.table', compact('clients'));
    }
}
