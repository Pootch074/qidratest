<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Transaction;

class PacdController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sectionId = $user->section_id;
        $columns = [
            'queue_number' => 'Queue Number',
            'client_type'  => 'Client Type',
            'step_id'      => 'Step',
            'section_id'   => 'Section',
            'queue_status' => 'Status',
        ];

        $transactions = Transaction::with('section')
            ->select(array_keys($columns)) // fetch only required fields
            ->where('section_id', $sectionId)
            ->latest()
            ->get();

        $sections = Section::where('id', $sectionId)->get();

        return view('pacd.index', compact('sections', 'transactions', 'columns'));
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

    // Create new transaction
    $transaction = Transaction::create([
        'queue_number' => $newQueueNumber,
        'client_type'  => $clientType,
        'window_id'    => null,
        'section_id'   => $section->id,
        'queue_status' => 'waiting',
    ]);

    // Build formatted queue label (R001, P001, etc.)
    $prefix = strtoupper(substr($clientType, 0, 1));
    $formattedQueue = $prefix . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

    return redirect()->back()
        ->with('success', "Queue #{$formattedQueue} created for {$section->section_name}");
}

}
