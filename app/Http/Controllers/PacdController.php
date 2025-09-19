<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\User;
use App\Models\Step;
use App\Models\Window;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $clientId   = $request->input('client_id');            // from scanning flow
        $clientName = $request->input('manual_client_name');   // from manual flow

        if ($clientId) {
            // 🔎 Scanned client flow → update existing record
            $client = Transaction::where('id', $clientId)
                ->whereNull('ticket_status')
                ->firstOrFail();
        } else {
            // 📝 Manual flow → create new record
            $client = new Transaction([
                'full_name'    => $clientName,
                'ticket_status' => null,
            ]);
        }

        // Get last queue number for this section + client_type
        $lastQueue = Transaction::where('section_id', $section->id)
            ->where('client_type', $clientType)
            ->max('queue_number');

        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // Get first step for this section
        $firstStep = Step::where('section_id', $section->id)
            ->where('step_number', 1)
            ->first();

        // Update or fill fields
        $client->fill([
            'queue_number' => $newQueueNumber,
            'client_type'  => $clientType,
            'step_id'      => $firstStep?->id,
            'window_id'    => null,
            'section_id'   => $section->id,
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
                $prefix = 'T';   // 👈 force returnee to use T
                break;
            default:
                $prefix = strtoupper(substr($clientType, 0, 1));
        }

        $formattedQueue = $prefix . str_pad($client->queue_number, 3, '0', STR_PAD_LEFT);

        // 👉 If JSON is expected (fetch / axios), return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'      => true,
                'queue_number' => $formattedQueue,
                'client_type'  => ucfirst($clientType),
                'client_name'  => $client->full_name,
                'section'      => $section->section_name,
            ]);
        }

        // 👉 Otherwise, fallback to redirect (normal form POST)
        return redirect()->back()
            ->with('success', "Queue #{$formattedQueue} created for {$section->section_name} (Client: {$client->full_name})");
    }

    public function transactionsTable()
    {
        $user = Auth::user();

        if (is_null($user->section_id)) {
            // No assigned section → show all transactions except section_id = 15
            $transactions = Transaction::with(['step', 'section'])
                ->whereNotIn('section_id', [15])
                ->where('queue_number', '>', 0) // ✅ Exclude queue_number = 0
                ->latest()
                ->get();
        } else {
            // User has assigned section → only their section's transactions
            $transactions = Transaction::with(['step', 'section'])
                ->where('section_id', $user->section_id)
                ->where('queue_number', '>', 0) // ✅ Exclude queue_number = 0
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

        $pendingQueues = Transaction::where('queue_status', 'pending')
            ->whereDate('updated_at', $yesterday)
            ->get();

        return view('pacd.pending.table', compact('pendingQueues'));
    }

    public function resumeTransaction($id)
    {
        // Force Manila timezone
        $now = Carbon::now('Asia/Manila');

        // Update the transaction
        DB::table('transactions')
            ->where('id', $id)
            ->update([
                'client_type' => 'returnee',
                'updated_at' => $now
            ]);

        return response()->json(['success' => true, 'message' => 'Transaction resumed']);
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
