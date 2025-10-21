<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Step;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacdController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $clients = Transaction::where('ticket_status', null)
            ->orderBy('id')
            ->get(['id', 'full_name', 'created_at']);

        // Sections for buttons
        if (is_null($user->section_id)) {
            // User has no assigned section → show all, except these IDs
            $sections = Section::whereNotIn('id', [2, 3, 4, 5, 6, 7, 8, 10, 11, 15])
                ->orderBy('section_name')
                ->get(['id', 'section_name']);
        } else {
            // User has assigned section → show only that section
            $sections = Section::where('id', $user->section_id)
                ->orderBy('section_name')
                ->get(['id', 'section_name']);
        }

        // ✅ Only pass sections here
        return view('pacd.index', compact('sections', 'clients'));
    }

    public function generateQueue(Request $request, Section $section)
    {
        $clientType = $request->input('client_type', 'regular');
        $clientId = $request->input('client_id');
        $clientName = $request->input('manual_client_name');

        if ($clientId) {
            // 🔎 Scanned client flow → update existing record
            $client = Transaction::where('id', $clientId)
                ->whereNull('ticket_status')
                ->firstOrFail();
        } else {
            // 📝 Manual flow → create new record
            $client = new Transaction([
                'full_name' => $clientName,
                'ticket_status' => null,
            ]);
        }

        // ✅ Only look at today’s records
        $today = Carbon::today();

        $lastQueue = Transaction::where('section_id', $section->id)
            ->where('client_type', $clientType)
            ->whereDate('created_at', $today)   // 👈 only today
            ->max('queue_number');

        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // Get first step for this section
        $firstStep = Step::where('section_id', $section->id)
            ->where('step_number', 1)
            ->first();

        // Fill and save
        $client->fill([
            'queue_number' => $newQueueNumber,
            'client_type' => $clientType,
            'step_id' => $firstStep?->id,
            'window_id' => null,
            'section_id' => $section->id,
            'queue_status' => 'waiting',
            'ticket_status' => 'issued',
        ]);

        $client->save();

        // Format queue label
        switch ($clientType) {
            case 'priority':
                $prefix = 'P';
                break;
            case 'regular':
                $prefix = 'R';
                break;
            case 'returnee':
                $prefix = 'T';
                break;
            default:
                $prefix = strtoupper(substr($clientType, 0, 1));
        }

        $formattedQueue = $prefix.str_pad($client->queue_number, 3, '0', STR_PAD_LEFT);

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

        if (is_null($user->section_id)) {
            // No assigned section → show all transactions except section_id = 15
            $transactions = Transaction::with(['step', 'section'])
                ->whereNotIn('section_id', [15])
                ->where('queue_number', '>', 0) // ✅ Exclude queue_number = 0
                ->whereDate('created_at', $today)
                ->whereDate('updated_at', $today) // ✅ Only today's transactions
                ->orderBy('queue_number', 'desc') // ✅ Order by queue_number descending
                ->latest()
                ->get();
        } else {
            // User has assigned section → only their section's transactions
            $transactions = Transaction::with(['step', 'section'])
                ->where('section_id', $user->section_id)
                ->where('queue_number', '>', 0) // ✅ Exclude queue_number = 0
                ->whereDate('created_at', $today)
                ->whereDate('updated_at', $today) // ✅ Only today's transactions
                ->orderBy('queue_number', 'desc') // ✅ Order by queue_number descending
                ->latest()
                ->get();
        }

        return view('pacd.transactions.table', compact('transactions'));
    }

    public function sectionsCards()
    {
        $user = Auth::user();

        // Sections for buttons
        if (is_null($user->section_id)) {
            // User has no assigned section → show all, except these IDs
            $sections = Section::whereNotIn('id', [2, 3, 4, 5, 6, 7, 8, 10, 11, 15])
                ->orderBy('section_name')
                ->get(['id', 'section_name']);
        } else {
            // User has assigned section → show only that section
            $sections = Section::where('id', $user->section_id)
                ->orderBy('section_name')
                ->get(['id', 'section_name']);
        }

        return view('pacd.sections.cards', compact('sections'));
    }

    public function pendingQueues()
    {
        $yesterday = Carbon::yesterday()->toDateString(); // now in Asia/Manila
        $sectionId = Auth::user()->section_id;

        $pendingQueues = Transaction::where('queue_status', 'deferred')
            ->where('ticket_status', 'issued')
            ->whereDate('created_at', $yesterday)
            ->get();

        return view('pacd.pending.table', compact('pendingQueues'));
    }

    public function resumeTransaction(Request $request, $id)
    {
        $now = Carbon::now('Asia/Manila');

        // Find the old transaction
        $oldTransaction = Transaction::findOrFail($id);

        // ✅ Mark old transaction as cancelled
        $oldTransaction->update([
            'ticket_status' => 'cancelled',
            'updated_at' => $now,
        ]);

        // Get last queue for returnee (today only)
        $today = Carbon::today('Asia/Manila')->toDateString();
        $lastQueue = Transaction::where('client_type', 'deferred')
            ->where('section_id', $oldTransaction->section_id)
            ->whereDate('created_at', $today)
            ->max('queue_number');

        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // Create a new transaction for returnee
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

        // Format queue number (T###)
        $formattedQueue = 'D'.str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'queue_number' => $formattedQueue,
            'full_name' => $transaction->full_name,
            'section' => $transaction->section->section_name ?? '',
            'step_number' => $transaction->step->step_number ?? '',
            'client_type' => ucfirst($transaction->client_type), // for printing
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    public function clientsTable()
    {
        $user = Auth::user();

        $query = Transaction::whereNull('ticket_status')
            ->orderBy('id');

        if (is_null($user->section_id)) {
            // User has no section → fetch only records where section_id is NULL
            $query->whereNull('section_id');
        } else {
            // User has a section → fetch only records matching their section_id
            $query->where('section_id', $user->section_id);
        }

        $clients = $query->get(['id', 'full_name', 'created_at']);

        return view('pacd.scanned_id.table', compact('clients'));
    }
}
