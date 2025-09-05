<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use App\Models\Division;
use App\Models\Step;
use App\Models\Window;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class UsersController extends Controller
{

    public function admin()
    {
        $user = Auth::user();
        $sectionId = $user->section_id;

        // ✅ Transaction counts filtered by user's section
        $waitingCount  = Transaction::where('queue_status', 'waiting')
            ->where('section_id', $sectionId)
            ->count();
        $pendingCount  = Transaction::where('queue_status', 'pending')
            ->where('section_id', $sectionId)
            ->count();
        $servingCount  = Transaction::where('queue_status', 'serving')
            ->where('section_id', $sectionId)
            ->count();

        $priorityCount = Transaction::where('client_type', 'priority')
            ->where('section_id', $sectionId)
            ->count();
        $regularCount  = Transaction::where('client_type', 'regular')
            ->where('section_id', $sectionId)
            ->count();

        // ✅ Users in the same section
        $userColumns = [
            'first_name'        => 'First Name',
            'last_name'         => 'Last Name',
            'email'             => 'Email',
            'position'          => 'Position',
            'user_type'         => 'User Type',
            'assigned_category' => 'Category',
            'window_id'         => 'Window ID',
        ];

        $users = User::where('section_id', $sectionId)
            ->latest()
            ->get();

        // ✅ Transactions filtered by section and ordered by latest queue_number
        $transactions = Transaction::where('section_id', $sectionId)
            ->orderBy('queue_number', 'desc')
            ->get();

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
        $authUser = Auth::user();
        $sectionId = $authUser->section_id;

        // Fetch users (with step and window relations)
        $users = User::with(['step', 'window'])
            ->where('section_id', $sectionId)
            ->latest()
            ->get();

        $userColumns = [
            'first_name'        => 'First Name',
            'last_name'         => 'Last Name',
            'email'             => 'Email',
            'position'          => 'Position',
            'user_type'         => 'User Type',
            'assigned_category' => 'Category',
        ];

        // Steps that belong to the user's section
        $steps = Step::where('section_id', $sectionId)->get();

        // Windows will be loaded dynamically by step
        $windows = []; // keep empty initially

        // User types excluding Admin
        $userTypes = collect(User::getUserTypes())
            ->except([User::TYPE_SUPERADMIN, User::TYPE_ADMIN, User::TYPE_IDSCAN, User::TYPE_PACD]);

        return view('admin.users.table', compact(
            'users',
            'userColumns',
            'steps',
            'windows',
            'userTypes'
        ));
    }

    public function usersJson()
    {
        $authUser = Auth::user();
        $sectionId = $authUser->section_id;

        $users = User::with(['step', 'window'])
            ->where('section_id', $sectionId)
            ->latest()
            ->get();

        $formatted = $users->map(function ($u) {
            return [
                'id' => $u->id,
                'first_name' => $u->first_name,
                'last_name' => $u->last_name,
                'email' => $u->email,
                'position' => $u->position,
                'user_type_name' => $u->getUserTypeName(),
                'assigned_category' => $u->assigned_category,
                'step_number' => $u->step?->step_number,
                'window_number' => $u->window?->window_number,
            ];
        });

        return response()->json($formatted);
    }



    
    public function user()
    {
        $user = Auth::user();
        $sectionId = $user->section_id;

        // ---------- UPCOMING QUEUES ----------
        $upcomingQueues = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->get();

        $regularQueues = $upcomingQueues->filter(fn($q) => strtolower($q->client_type) === 'regular');
        $priorityQueues = $upcomingQueues->filter(fn($q) => strtolower($q->client_type) === 'priority');

        $regularQueues->transform(fn($q) => tap($q, function ($q) {
            $q->formatted_number = 'R' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT);
            $q->style_class = 'bg-[#150e60]';
        }));

        $priorityQueues->transform(fn($q) => tap($q, function ($q) {
            $q->formatted_number = 'P' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT);
            $q->style_class = 'bg-red-600';
        }));

        // ---------- PENDING QUEUES ----------
        $pendingQueues = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $pendingRegularQueues = $pendingQueues->filter(fn($q) => strtolower($q->client_type) === 'regular');
        $pendingPriorityQueues = $pendingQueues->filter(fn($q) => strtolower($q->client_type) === 'priority');

        $pendingRegularQueues->transform(fn($q) => tap($q, function ($q) {
            $q->formatted_number = 'R' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT);
            $q->style_class = 'bg-[#150e60]';
        }));

        $pendingPriorityQueues->transform(fn($q) => tap($q, function ($q) {
            $q->formatted_number = 'P' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT);
            $q->style_class = 'bg-red-600';
        }));

        // --- Fetch serving queue ---
        $servingQueue = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'serving')
            ->orderBy('updated_at', 'desc')
            ->get();

        // --- Get logged-in user's step number, window number, and field office ---
        $stepNumber = $user->step ? $user->step->step_number : null;
        $windowNumber = $user->window ? $user->window->window_number : null;

        // Field office via section → division → office
        $fieldOffice = optional($user->section)
            ->division
            ->office
            ->field_office ?? null;

        $divisionName = optional($user->section)->division->division_name ?? null;
        $sectionName = optional($user->section)->section_name ?? null;

        // --- Pass all to view ---
        return view('user.index', compact(
            'regularQueues',
            'priorityQueues',
            'pendingRegularQueues',
            'pendingPriorityQueues',
            'servingQueue',
            'stepNumber',
            'windowNumber',
            'fieldOffice',
            'divisionName',
            'sectionName'
        ));
    }



    public function fetchQueues()
    {
        $user = Auth::user();
        $sectionId = $user->section_id;

        // Helper function to format queues
        $formatQueues = function ($queues) {
            return $queues->map(function ($q) {
                $q->formatted_number = strtoupper(substr($q->client_type, 0, 1))
                    . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT);
                $q->style_class = strtolower($q->client_type) === 'priority' ? 'bg-red-600' : 'bg-[#150e60]';
                return $q;
            })->values();
        };

        // Upcoming
        $upcoming = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'waiting')
            ->orderBy('created_at', 'asc')->get();
        $regularQueues = $formatQueues($upcoming->where('client_type', 'regular'));
        $priorityQueues = $formatQueues($upcoming->where('client_type', 'priority'));

        // Pending
        $pending = Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'pending')
            ->orderBy('created_at', 'asc')->get();
        $pendingRegular = $formatQueues($pending->where('client_type', 'regular'));
        $pendingPriority = $formatQueues($pending->where('client_type', 'priority'));

        // Serving
        $servingQueue = $formatQueues(Transaction::where('section_id', $sectionId)
            ->where('queue_status', 'serving')
            ->orderBy('updated_at', 'desc')
            ->get());

        // User info
        $userInfo = [
            'stepNumber' => optional($user->step)->step_number ?? 'N/A',
            'windowNumber' => optional($user->window)->window_number ?? 'N/A',
            'sectionName' => optional($user->section)->section_name ?? 'N/A',
            'divisionName' => optional(optional($user->section)->division)->division_name ?? 'N/A',
            'fieldOffice' => $user->field_office ?? 'N/A',
        ];

        return response()->json([
            'regularQueues' => $regularQueues,
            'priorityQueues' => $priorityQueues,
            'pendingRegular' => $pendingRegular,
            'pendingPriority' => $pendingPriority,
            'servingQueue' => $servingQueue,
            'userInfo' => $userInfo
        ]);
    }







    public function store(Request $request)
    {
        $authUser = Auth::user();
        $sectionId = $authUser->section_id;

        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'position'          => 'required|string|max:255',
            'user_type'         => 'required|string',
            'assigned_category' => 'required|string|in:regular,priority',
            'step_id'           => 'required|exists:steps,id',
            'window_id'         => 'required|exists:windows,id',
            'password'          => 'required|string|min:6',
        ]);

        // Create new user
        $user = User::create([
            'first_name'        => $validated['first_name'],
            'last_name'         => $validated['last_name'],
            'email'             => $validated['email'],
            'position'          => $validated['position'],
            'user_type'         => $validated['user_type'],
            'assigned_category' => $validated['assigned_category'],
            'step_id'           => $validated['step_id'],
            'window_id'         => $validated['window_id'],
            'section_id'        => $sectionId,
            'password'          => bcrypt($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'id'               => $user->id,
                'first_name'       => $user->first_name,
                'last_name'        => $user->last_name,
                'email'            => $user->email,
                'position'         => $user->position,
                'user_type_name'   => $user->getUserTypeName(),
                'assigned_category'=> $user->assigned_category,
                'window_number'    => $user->window->window_number ?? null,
                'step_number'      => $user->step->step_number ?? null,
            ],
        ]);
    }


    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }





    public function nextRegular()
{
    $user = Auth::user();

    // Validate required fields exist
    if (!$user->step_id || !$user->window_id || !$user->section_id) {
        return response()->json([
            'status' => 'error',
            'message' => 'User is not assigned to a step, window, or section.'
        ], 400);
    }

    // Use transaction + lock to prevent race conditions
    $transaction = DB::transaction(function () use ($user) {
        $record = Transaction::where('queue_status', 'waiting')
            ->where('client_type', 'regular')
            ->where('step_id', $user->step_id)
            ->where('window_id', $user->window_id)
            ->where('section_id', $user->section_id)
            ->lockForUpdate() // prevent concurrent grabs
            ->orderBy('created_at', 'asc')
            ->first();

        if ($record) {
            $record->update([
                'queue_status' => 'serving',
            ]);
        }

        return $record;
    });

    if ($transaction) {
        return response()->json([
            'status' => 'success',
            'message' => "Serving client {$transaction->client_type}{$transaction->queue_number}",
            'transaction' => $transaction
        ]);
    }

    return response()->json([
        'status' => 'empty',
        'message' => 'No regular clients waiting in the queue.'
    ]);
}


public function getWindowsByStep($stepId)
    {
        $authUser = Auth::user();
        $sectionId = $authUser->section_id;

        $windows = Window::where('step_id', $stepId)
            ->whereHas('step', function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            })
            ->get(['id', 'window_number']);

        return response()->json($windows);
    }










}
