<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class QueueController extends Controller
{
    public function store(Request $request)
    {
        // Get latest queue number
        $lastTransaction = Transaction::orderBy('id', 'desc')->first();
        $newNumber = $lastTransaction ? $lastTransaction->queue_number + 1 : 1;

        // Insert into transactions table
        $transaction = Transaction::create([
            'queue_number' => $newNumber,
            'client_type'  => $request->input('client_type', 'regular'), // default regular
            'window_id'    => $request->input('window_id', 0), // default 0 if no window assigned yet
            'queue_status' => 'waiting',
        ]);

        return response()->json([
            'success' => true,
            'number'  => $transaction->queue_number
        ]);
    }
}
