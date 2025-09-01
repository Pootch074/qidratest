<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use App\Models\Step;
use App\Models\Window;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UsersController extends Controller
{

    public function admin()
    {
        $user = Auth::user();
        $userColumns = [
            'first_name'       => 'First Name',
            'last_name'        => 'Last Name',
            'email'            => 'Email',
            'position'         => 'Position',
            'user_type'        => 'User Type',
            'assigned_category' => 'Category',
            'window_id'        => 'Window ID',
        ];

        $users = User::where('section_id', $user->section_id)
            ->latest()
            ->get();

        $transactions = Transaction::orderBy('queue_number', 'desc')->get();
        return view('admin.index', compact('transactions', 'users', 'userColumns'));
    }
    public function users()
    {
        $user = Auth::user();

        $userColumns = [
            'first_name'        => 'First Name',
            'last_name'         => 'Last Name',
            'email'             => 'Email',
            'position'          => 'Position',
            'user_type'         => 'User Type',
            'assigned_category' => 'Category',
        ];

        $users = User::with(['step', 'window'])
            ->where('section_id', $user->section_id)
            ->latest()
            ->get();


        // Fetch steps that belong to the user's section
        $steps = Step::whereHas('section', function ($query) use ($user) {
            $query->where('id', $user->section_id);
        })->get();

        // Fetch windows that belong to steps in the user's section
        $windows = Window::whereHas('step', function ($q) use ($user) {
            $q->where('section_id', $user->section_id);
        })->get();


        return view('admin.users.table', compact('users', 'userColumns', 'steps', 'windows'));
    }









    public function pacd()
    {
        $sections = Section::orderBy('section_name')->get(['id', 'section_name']);
        return view('pacd.index', compact('sections'));
    }
    public function preassess()
    {
        return view('preassess.index');
    }
    public function encode()
    {
        return view('encode.index');
    }
    public function assessment()
    {
        return view('assessment.index');
    }
    public function release()
    {
        return view('release.index');
    }
    public function user()
    {
        $sectionId = Auth::user()->section_id;
        // Upcoming queues: status = 'waiting'
        $upcomingQueues = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->get();

        // Format each pending queue
        $upcomingQueues->transform(function ($queue) {
            $prefix = strtolower($queue->client_type) === 'priority' ? 'P' : 'R';
            $queue->sdsddses = $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            return $queue;
        });


        // Pending queues: status = 'pending'
        $pendingQueues = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // Format each pending queue
        $pendingQueues->transform(function ($queue) {
            $prefix = strtolower($queue->client_type) === 'priority' ? 'P' : 'R';
            $queue->nchdfm = $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            return $queue;
        });

        // Get the currently serving queue
        $servingQueue = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'serving')
            ->orderBy('updated_at', 'desc') // latest serving
            ->get(); // get a single record

        $servingQueue->transform(function ($queue) {
            $prefix = strtolower($queue->client_type) === 'priority' ? 'P' : 'R';
            $queue->lfgofkf = $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            return $queue;
        });




        return view('user.index', compact('upcomingQueues', 'pendingQueues', 'servingQueue'));
    }




    public function store(Request $request)
    {
        $authUser = Auth::user();

        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'position'          => 'required|string|max:255',
            'user_type'         => 'required|in:1,6',
            'assigned_category' => 'required|in:regular,priority',
            'step_id'           => 'required|exists:steps,id',
            'window_id'         => 'required|exists:windows,id',
            'password'          => 'required|string|min:6',
        ]);

        // Automatically attach to the logged-in user's section
        $validated['section_id'] = $authUser->section_id;

        // Hash the password
        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
