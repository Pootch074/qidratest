<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\User;
use App\Models\Step;
use App\Models\Window;
use App\Models\Client;
use App\Models\Transaction;
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
            $sections = Section::whereNotIn('id', [2,3,4,5,6,7,8,10,11,15])
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
        $clientType = $request->input('client_type', 'regular'); // default to regular
        $clientId   = $request->input('client_id');

        // 🔎 Find the client waiting in the "scanned IDs" list
        $client = Transaction::where('id', $clientId)
            ->whereNull('ticket_status')
            ->firstOrFail();

        // Get the highest queue_number for this section + client_type
        $lastQueue = Transaction::where('section_id', $section->id)
            ->where('client_type', $clientType)
            ->max('queue_number');

        // Increment or start at 1
        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // ✅ Get the first step for this section (step_number = 1)
        $firstStep = Step::where('section_id', $section->id)
            ->where('step_number', 1)
            ->first();

        // 🔄 Update the existing client record instead of creating a new one
        $client->update([
            'queue_number' => $newQueueNumber,
            'client_type'  => $clientType,
            'step_id'      => $firstStep ? $firstStep->id : null,
            'window_id'    => null,
            'section_id'   => $section->id,
            'queue_status' => 'waiting',
            'ticket_status'=> 'issued',
        ]);

        // Build formatted queue label (R001, P001, etc.)
        $prefix = strtoupper(substr($clientType, 0, 1));
        $formattedQueue = $prefix . str_pad($client->queue_number, 3, '0', STR_PAD_LEFT);

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
            $sections = Section::whereNotIn('id', [2,3,4,5,6,7,8,10,11,15])
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
