<?php

namespace App\Http\Controllers;

use App\Enums\ClientType;
use App\Enums\QueueStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function store(Request $request)
    {
        $clientType = ClientType::from($request->input('client_type', ClientType::REGULAR->value));

        $lastTransaction = Transaction::where('client_type', $clientType->value)
            ->orderBy('queue_number', 'desc')
            ->first();

        $newNumber = $lastTransaction ? $lastTransaction->queue_number + 1 : 1;

        $transaction = Transaction::create([
            'queue_number' => $newNumber,
            'client_type' => $clientType,
            'window_id' => $request->input('window_id', 2),
            'queue_status' => QueueStatus::WAITING,
        ]);

        $displayNumber = $clientType->prefix().str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'success' => true,
            'queue_number' => $transaction->queue_number,
            'client_type' => $transaction->client_type->value,
            'queue_status' => $transaction->queue_status->value,
            'window_id' => $transaction->window_id,
            'display_number' => $displayNumber,
        ]);
    }
}
