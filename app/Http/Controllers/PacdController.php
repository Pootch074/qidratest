<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Transaction;

class PacdController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get();
        $sections = Section::all(); // fetch all sections
        return view('pacd.index', compact('sections', 'transactions'));
    }

    public function generateQueue(Request $request, Section $section)
    {
        // Get the highest queue_number for this specific section
        $lastQueue = Transaction::where('section_id', $section->id)->max('queue_number');

        // Increment or start at 1
        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // Create new transaction
        $transaction = Transaction::create([
            'queue_number' => $newQueueNumber,
            'client_type'  => $request->input('client_type', 'regular'),
            'step_id'  => $request->input('step_id', 1),
            'window_id'    => null,
            'section_id'   => $section->id,   // ✅ store section ID
            'queue_status' => 'waiting',
        ]);

        return redirect()->back()
            ->with('success', "Queue #{$transaction->queue_number} created for {$section->section_name}");
    }
}
