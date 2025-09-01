<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class QueueController extends Controller
{
    public function upcomingQueue()
    {
        $sectionId = Auth::user()->section_id;
        $queueNumbers = Transaction::where('section_id', $sectionId)
            ->orderBy('created_at', 'asc') // Optional: order by arrival
            ->pluck('queue_number'); // Get only the queue_number column

        return view('user.index', compact('queueNumbers'));
    }
}
