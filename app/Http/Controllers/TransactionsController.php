<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    
    public function store(Request $request)
    {
        $clientType = $request->input('client_type', 'regular'); 
        $lastTransaction = Transaction::where('client_type', $clientType)
            ->orderBy('queue_number', 'desc')
            ->first();

        $newNumber = $lastTransaction ? $lastTransaction->queue_number + 1 : 1;
        $transaction = Transaction::create([
            'queue_number' => $newNumber,
            'client_type'  => $clientType,
            'window_id'    => $request->input('window_id', 2),
            'queue_status' => 'waiting',
        ]);
        $prefix = strtoupper(substr($transaction->client_type, 0, 1));
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

    public function realtimeTransactions()
    {
        $user = Auth::user();
        $sectionId = $user->section_id;

        $transactions = Transaction::where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->orderBy('queue_number', 'desc')
            ->get();

        $counts = [
            'waitingCount'   => $transactions->where('queue_status', 'waiting')->count(),
            'pendingCount'   => $transactions->where('queue_status', 'pending')->count(),
            'servingCount'   => $transactions->where('queue_status', 'serving')->count(),
            'priorityCount'  => $transactions->where('client_type', 'priority')->count(),
            'regularCount'   => $transactions->where('client_type', 'regular')->count(),
            'returneeCount'   => $transactions->where('client_type', 'deferred')->count(),
            'completedCount' => $transactions->where('queue_status', 'completed')->count(),
        ];
        $tableHtml = view('admin.transactions.table', compact('transactions'))->render();

        return response()->json([
            'counts' => $counts,
            'table'  => $tableHtml,
        ]);
    }

}
