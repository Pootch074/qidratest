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

        $waitingCount  = Transaction::where('queue_status', 'waiting')->count();
        $pendingCount  = Transaction::where('queue_status', 'pending')->count();
        $servingCount  = Transaction::where('queue_status', 'serving')->count();

        $priorityCount = Transaction::where('client_type', 'priority')->count();
        $regularCount  = Transaction::where('client_type', 'regular')->count();

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
        return view('admin.index', compact(
            'transactions',
            'users',
            'userColumns',
            'waitingCount',
            'pendingCount',
            'servingCount',
            'priorityCount',
            'regularCount'
        ));
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

        // Split into regular and priority queues
        $regularQueues = $upcomingQueues->filter(fn($q) => strtolower($q->client_type) === 'regular');
        $priorityQueues = $upcomingQueues->filter(fn($q) => strtolower($q->client_type) === 'priority');

        // Format each queue
        $regularQueues->transform(function ($queue) {
            $prefix = 'R';
            $queue->formatted_number = $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            $queue->style_class = 'bg-[#150e60]'; // dark blue for regular
            return $queue;
        });

        $priorityQueues->transform(function ($queue) {
            $prefix = 'P';
            $queue->formatted_number = $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            $queue->style_class = 'bg-red-600'; // red for priority
            return $queue;
        });

        $pendingQueues = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // Split into regular and priority queues
        $pendingRegularQueues = $pendingQueues->filter(fn($q) => strtolower($q->client_type) === 'regular');
        $pendingPriorityQueues = $pendingQueues->filter(fn($q) => strtolower($q->client_type) === 'priority');

        // Format each queue
        $pendingRegularQueues->transform(function ($queue) {
            $prefix = 'R';
            $queue->formatted_number = $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            $queue->style_class = 'bg-[#150e60]'; // dark blue
            return $queue;
        });

        $pendingPriorityQueues->transform(function ($queue) {
            $prefix = 'P';
            $queue->formatted_number = $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            $queue->style_class = 'bg-red-600'; // red
            return $queue;
        });

        $servingQueue = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'serving')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('user.index', compact(
            'regularQueues',
            'priorityQueues',
            'pendingRegularQueues',
            'pendingPriorityQueues',
            'servingQueue'
        ));
    }

    public function fetchQueues()
    {
        $sectionId = Auth::user()->section_id;

        // Upcoming
        $upcomingQueues = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->get();

        $regularQueues = $upcomingQueues->filter(fn($q) => strtolower($q->client_type) === 'regular');
        $priorityQueues = $upcomingQueues->filter(fn($q) => strtolower($q->client_type) === 'priority');

        $regularQueues->transform(function ($queue) {
            $queue->formatted_number = 'R' . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            $queue->style_class = 'bg-[#150e60]';
            return $queue;
        });

        $priorityQueues->transform(function ($queue) {
            $queue->formatted_number = 'P' . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            $queue->style_class = 'bg-red-600';
            return $queue;
        });

        // Pending
        $pendingQueues = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $pendingRegular = $pendingQueues->filter(fn($q) => strtolower($q->client_type) === 'regular');
        $pendingPriority = $pendingQueues->filter(fn($q) => strtolower($q->client_type) === 'priority');

        $pendingRegular->transform(function ($queue) {
            $queue->formatted_number = 'R' . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            $queue->style_class = 'bg-[#150e60]';
            return $queue;
        });

        $pendingPriority->transform(function ($queue) {
            $queue->formatted_number = 'P' . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT);
            $queue->style_class = 'bg-red-600';
            return $queue;
        });

        return response()->json([
            'regularQueues' => $regularQueues->values(),
            'priorityQueues' => $priorityQueues->values(),
            'pendingRegular' => $pendingRegular->values(),
            'pendingPriority' => $pendingPriority->values(),
        ]);
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
