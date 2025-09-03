<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;


class PacdController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Sections for buttons
        $sections = Section::orderBy('section_name')->get(['id', 'section_name']);

        // Transactions
        if ($user->user_type == User::TYPE_PACD) {
            // PACD sees all transactions
            $transactions = Transaction::with(['section', 'step'])
                ->orderBy('queue_number', 'desc')
                ->get();
        } else {
            // Normal users see only their section
            $transactions = Transaction::with(['section', 'step'])
                ->where('section_id', $user->section_id)
                ->orderBy('queue_number', 'desc')
                ->get();
        }

        return view('pacd.index', compact('sections', 'transactions'));
    }

    public function generateQueue(Request $request, Section $section)
    {
        $clientType = $request->input('client_type', 'regular'); // default to regular

        // Get the highest queue_number for this specific section + client_type
        $lastQueue = Transaction::where('section_id', $section->id)
            ->where('client_type', $clientType)
            ->max('queue_number');

        // Increment or start at 1
        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // ✅ Get the first step for this section (step_number = 1)
        $firstStep = \App\Models\Step::where('section_id', $section->id)
            ->where('step_number', 1)
            ->first();

        // ✅ Find the window associated with this step
        $window = null;
        if ($firstStep) {
            $window = \App\Models\Window::where('step_id', $firstStep->id)->first();
        }

        // Create new transaction
        $transaction = Transaction::create([
            'queue_number' => $newQueueNumber,
            'client_type'  => $clientType,
            'step_id'      => $firstStep ? $firstStep->id : null, // ✅ Assign step_id
            'window_id'    => $window ? $window->id : null,       // ✅ Assign window_id
            'section_id'   => $section->id,
            'queue_status' => 'waiting',
        ]);

        // Build formatted queue label (R001, P001, etc.)
        $prefix = strtoupper(substr($clientType, 0, 1));
        $formattedQueue = $prefix . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

        return redirect()->back()
            ->with('success', "Queue #{$formattedQueue} created for {$section->section_name}");
    }



    public function transactionsTable()
    {
        $user = Auth::user();

        if ($user->user_type == 7) { // PACD sees all sections
            $transactions = Transaction::with(['step', 'section'])->latest()->get();
        } else {
            $transactions = Transaction::with(['step', 'section'])
                ->where('section_id', $user->section_id)
                ->latest()
                ->get();
        }

        return view('pacd.transactions.table', compact('transactions'));
    }
}
