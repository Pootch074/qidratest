<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encoder;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB; // ✅ add this

class EncodersController extends Controller
{
    public function index()
    {
        $upcoming = DB::table('transactions')
            ->select('queue_number', 'client_type') // include client_type
            ->orderBy('id')
            ->get()
            ->map(function ($item) {
                // determine prefix based on client type
                $prefix = $item->client_type === 'priority' ? 'P' : 'R';
                $item->queue_number = $prefix . str_pad($item->queue_number, 3, '0', STR_PAD_LEFT);
                return $item;
            });

        return view('encode.index', compact('upcoming'));
    }
}
