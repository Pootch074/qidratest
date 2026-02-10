<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Step;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PacdController extends Controller
{
    protected $excludedSectionNames = [
        'ACCOUNTING SECTION',
        'PROPERTY AND SUPPLY SECTION',
        'RECORDS AND ARCHIVE MANAGEMENT SECTION',
        'HR PERSONNEL ADMINISTRATION SECTION',
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
        $now = Carbon::now('Asia/Manila');

        $transaction = DB::transaction(function () use ($request, $section, $now) {
            $clientType = $request->input('client_type', 'regular');
            $clientName = $request->input('manual_client_name');
            $clientPhone = $request->input('manual_client_phone');

            // Compute new queue number safely
            $lastQueue = Transaction::forSection($section->id)
                ->ofClientType($clientType)
                ->today()
                ->lockForUpdate()
                ->max('queue_number');

            $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

            // Get first step dynamically
            $firstStep = Step::where('section_id', $section->id)
                ->where('step_number', 1)
                ->first();

            // Create and save new client transaction
            $client = new Transaction;

            $client->fill([
                'full_name' => $clientName,
                'phone_number' => $clientPhone,  // <- now saved correctly
                'queue_number' => $newQueueNumber,
                'client_type' => $clientType,
                'step_id' => $firstStep?->id,
                'window_id' => null,
                'section_id' => $section->id,
                'queue_status' => 'waiting',
                'ticket_status' => 'issued',
                'updated_at' => $now,
            ]);

            $client->save();

            return $client;
        });

        $prefixMap = ['priority' => 'P', 'regular' => 'R', 'deferred' => 'D'];
        $clientTypeValue = $transaction->client_type->value ?? $transaction->client_type;
        $prefix = $prefixMap[$clientTypeValue] ?? strtoupper(substr($clientTypeValue, 0, 1));
        $formattedQueue = $prefix.str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'queue_number' => $formattedQueue,
                'client_type' => ucfirst($transaction->client_type->value ?? $transaction->client_type),
                'client_name' => $transaction->full_name,
                'client_phone' => $transaction->phone_number,  // <- returned in JSON
                'section' => $transaction->section->section_name,
            ]);
        }

        return redirect()->back()
            ->with('success', "Queue #{$formattedQueue} created for {$transaction->section->section_name} (Client: {$transaction->full_name})");
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

        $transaction = DB::transaction(function () use ($request, $id, $now) {
            $oldTransaction = Transaction::findOrFail($id);

            // Cancel the old ticket
            $oldTransaction->update([
                'ticket_status' => 'cancelled',
                'updated_at' => $now,
            ]);

            // Generate next deferred queue number for this section
            $lastQueue = Transaction::ofClientType('deferred')
                ->forSection($oldTransaction->section_id)
                ->today()
                ->lockForUpdate()
                ->max('queue_number');

            $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

            // Create the new deferred transaction
            $newTransaction = Transaction::create([
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

            return $newTransaction;
        });

        // Everything committed successfully
        $formattedQueue = 'D'.str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'queue_number' => $formattedQueue,
            'full_name' => $transaction->full_name,
            'section' => $transaction->section->section_name ?? '',
            'step_number' => $transaction->step->step_number ?? '',
            'client_type' => ucfirst($transaction->client_type->value),
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    public function clientsTable()
    {
        $user = Auth::user();

        $clientlogs = Transaction::where('created_at', '>=', now()->startOfDay())
            ->where('created_at', '<=', now()->endOfDay())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pacd.clients.table', compact('clientlogs'));
    }
}
