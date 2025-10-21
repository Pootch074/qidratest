<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

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
