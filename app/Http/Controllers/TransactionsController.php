<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionsController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('admin.transactions.table', compact('transactions'));
    }
    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
    public function store(Request $request)
    {
        $clientType = $request->input('client_type', 'regular'); // default: regular

        // Get the latest queue number for this client_type
        $lastTransaction = Transaction::where('client_type', $clientType)
            ->orderBy('queue_number', 'desc')
            ->first();

        $newNumber = $lastTransaction ? $lastTransaction->queue_number + 1 : 1;

        // Create new transaction
        $transaction = Transaction::create([
            'queue_number' => $newNumber,
            'client_type'  => $clientType,
            'window_id'    => $request->input('window_id', 2),
            'queue_status' => 'waiting',
        ]);

        // Build display number: first letter of client_type + zero-padded number
        $prefix = strtoupper(substr($transaction->client_type, 0, 1)); // R or P
        $displayNumber = $prefix . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'success'        => true,
            'queue_number'   => $transaction->queue_number,
            'client_type'    => $transaction->client_type,
            'queue_status'   => $transaction->queue_status,
            'window_id'      => $transaction->window_id,
            'display_number' => $displayNumber,
        ]);
    }
}
