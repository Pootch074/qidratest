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
    private function getQueuesForUser($user)
    {
        $sectionId = $user->section_id;

        // Formatter for queues
        $formatQueues = function ($queues) {
            return $queues->map(function ($q) {
                $q->formatted_number = strtoupper(substr($q->client_type, 0, 1))
                    . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT);
                $q->style_class = strtolower($q->client_type) === 'priority'
                    ? 'bg-red-600'
                    : 'bg-[#150e60]';
                return $q;
            })->values();
        };

        return [
            'regularQueues' => $formatQueues(Transaction::where('section_id', $sectionId)
                ->where('step_id', $user->step_id)
                ->where('queue_status', 'waiting')
                ->where('client_type', 'regular')
                ->orderBy('created_at', 'asc')
                ->get()),

            'priorityQueues' => $formatQueues(Transaction::where('section_id', $sectionId)
                ->where('step_id', $user->step_id)
                ->where('queue_status', 'waiting')
                ->where('client_type', 'priority')
                ->orderBy('created_at', 'asc')
                ->get()),

            'pendingRegularQueues' => $formatQueues(Transaction::where('section_id', $sectionId)
                ->where('queue_status', 'pending')
                ->where('client_type', 'regular')
                ->orderBy('created_at', 'asc')
                ->get()),

            'pendingPriorityQueues' => $formatQueues(Transaction::where('section_id', $sectionId)
                ->where('queue_status', 'pending')
                ->where('client_type', 'priority')
                ->orderBy('created_at', 'asc')
                ->get()),

            'servingQueue' => $formatQueues(Transaction::where('section_id', $sectionId)
                ->where('queue_status', 'serving')
                ->where('step_id', Auth::user()->step_id)
                ->where('window_id', Auth::user()->window_id)
                ->orderBy('updated_at', 'desc')
                ->get()),
        ];
    }


    public function user()
    {
        $user = Auth::user();
        logger()->info('Logged-in user step_id: ' . $user->step_id);

        $queues = $this->getQueuesForUser($user);

        $stepNumber   = optional($user->step)->step_number;
        $windowNumber = optional($user->window)->window_number;
        $fieldOffice  = optional($user->section?->division?->office)->field_office;
        $divisionName = optional($user->section?->division)->division_name;
        $sectionName  = optional($user->section)->section_name;

        return view('user.index', array_merge($queues, compact(
            'stepNumber',
            'windowNumber',
            'fieldOffice',
            'divisionName',
            'sectionName'
        )));
    }

    /**
     * User dashboard (JSON endpoint for polling/AJAX).
     */
    public function fetchQueues()
    {
        $user = Auth::user();
        $queues = $this->getQueuesForUser($user);

        $userInfo = [
            'stepNumber'   => optional($user->step)->step_number ?? 'N/A',
            'windowNumber' => optional($user->window)->window_number ?? 'N/A',
            'sectionName'  => optional($user->section)->section_name ?? 'N/A',
            'divisionName' => optional($user->section?->division)->division_name ?? 'N/A',
            'fieldOffice'  => optional($user->section?->division?->office)->field_office ?? 'N/A',
        ];

        return response()->json(array_merge($queues, [
            'userInfo' => $userInfo
        ]));
    }

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
        $completedCount  = Transaction::where('queue_status', 'completed')
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
            'regularCount',
            'completedCount'
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


    public function store(Request $request)
{
    $authUser = Auth::user();
    $sectionId = $authUser->section_id;

    // 🟢 Validation
    $validated = $request->validate([
        'first_name'        => 'required|string|max:255',
        'last_name'         => 'required|string|max:255',
        'email'             => 'required|email|unique:users,email',
        'position'          => 'required|string|max:255',
        // 👇 allow "both" only if section_id == 15
        'assigned_category' => $sectionId == 15
            ? 'required|string|in:regular,priority,both'
            : 'nullable|string',
        'step_id'           => 'required|exists:steps,id',
        'window_id'         => 'required|exists:windows,id',
        'password'          => 'required|string|min:6',
    ]);

    // 🛡️ Force defaults
    $validated['user_type'] = 5; // always TYPE_USER

    if ($sectionId != 15) {
        // 🔒 override regardless of input
        $validated['assigned_category'] = 'both';
    }

    $user = User::create([
        'first_name'        => $validated['first_name'],
        'last_name'         => $validated['last_name'],
        'email'             => $validated['email'],
        'position'          => $validated['position'],
        'user_type'         => $validated['user_type'],
        'assigned_category' => $validated['assigned_category'],
        'step_id'           => $validated['step_id'],
        'window_id'         => $validated['window_id'] ?? null,
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
        if (!$user->step_id || !$user->section_id || !$user->window_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not assigned to a step, section, or window.'
            ], 400);
        }

        // Use transaction + lock to prevent race conditions
        $transaction = DB::transaction(function () use ($user) {
            $record = Transaction::where('queue_status', 'waiting')
                ->where('client_type', 'regular')
                ->where('step_id', $user->step_id)
                ->where('section_id', $user->section_id)
                ->lockForUpdate() // prevent concurrent grabs
                ->orderBy('created_at', 'asc')
                ->first();

            if ($record) {
                $record->update([
                    'queue_status' => 'serving',
                    'window_id'    => $user->window_id, // ✅ assign current cashier's window
                ]);
            }

            return $record;
        });

        if ($transaction) {
            return response()->json([
                'status' => 'success',
                // 'message' => "Serving client {$transaction->client_type}{$transaction->queue_number} at window {$user->window_id}",
                'transaction' => $transaction
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'No regular clients waiting in the queue.'
        ]);
    }



    public function nextPriority()
    {
        $user = Auth::user();

        if (!$user->step_id || !$user->section_id || !$user->window_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not assigned to a step, section, or window.'
            ], 400);
        }

        $transaction = DB::transaction(function () use ($user) {
            $record = Transaction::where('queue_status', 'waiting')
                ->where('client_type', 'priority')
                ->where('step_id', $user->step_id)
                ->where('section_id', $user->section_id)
                ->lockForUpdate()
                ->orderBy('created_at', 'asc')
                ->first();

            if ($record) {
                $record->update([
                    'queue_status' => 'serving',
                    'window_id'    => $user->window_id,
                ]);
            }

            return $record;
        });

        if ($transaction) {
            return response()->json([
                'status' => 'success',
                'message' => "Serving PRIORITY client {$transaction->client_type}{$transaction->queue_number} at window {$user->window_id}",
                'transaction' => $transaction
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'No priority clients waiting in the queue.'
        ]);
    }


    
    public function skipQueue()
    {
        $user = Auth::user();

        if (!$user->step_id || !$user->section_id || !$user->window_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not assigned to a step, section, or window.'
            ], 400);
        }

        $transaction = DB::transaction(function () use ($user) {
            // Find the currently serving transaction
            $current = Transaction::where('queue_status', 'serving')
                ->where('section_id', $user->section_id)
                ->where('step_id', $user->step_id)
                ->where('window_id', $user->window_id)
                ->lockForUpdate()
                ->first();

            if (!$current) {
                return null;
            }

            // Move it back to pending and clear window
            $current->update([
                'queue_status' => 'pending',
                'window_id' => null,
            ]);

            return $current;
        });

        if ($transaction) {
            return response()->json([
                'status' => 'success',
                'message' => "Queue {$transaction->client_type}{$transaction->queue_number} has been skipped."
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'No active serving queue to skip.'
        ]);
    }

    public function proceedQueue()
{
    $user = Auth::user();

    if (!$user->step_id || !$user->section_id || !$user->window_id) {
        return response()->json([
            'status' => 'error',
            'message' => 'User is not assigned to a step, section, or window.'
        ], 400);
    }

    $transaction = DB::transaction(function () use ($user) {
        // Find the currently serving transaction
        $current = Transaction::where('queue_status', 'serving')
            ->where('section_id', $user->section_id)
            ->where('step_id', $user->step_id)
            ->where('window_id', $user->window_id)
            ->lockForUpdate()
            ->first();

        if (!$current) {
            return null;
        }

        // Find the next step in the same section
        $nextStep = Step::where('section_id', $user->section_id)
            ->where('step_number', '>', $user->step->step_number)
            ->orderBy('step_number', 'asc')
            ->first();

        if ($nextStep) {
            // Move to the next step
            $current->update([
                'step_id'      => $nextStep->id,
                'queue_status' => 'waiting',
                'window_id'    => null,
            ]);
        } else {
            // ✅ No next step -> mark as completed
            $current->update([
                'queue_status' => 'completed',
                'window_id'    => null,
            ]);
        }

        return $current;
    });

    if ($transaction) {
        return response()->json([
            'status'  => 'success',
            'message' => $transaction->queue_status === 'completed'
                ? 'Queue has been completed.'
                : 'Queue successfully proceeded to the next step.',
        ]);
    }

    return response()->json([
        'status' => 'empty',
        'message' => 'No active serving queue to proceed.'
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


    public function getQueues()
    {
        $user = Auth::user();

        // Base query (scoped to logged-in user's section/step/window)
        $baseQuery = Transaction::where('section_id', $user->section_id)
            ->where('step_id', $user->step_id);

        // 🔹 Upcoming (waiting)
        $regularQueues = (clone $baseQuery)
            ->where('queue_status', 'waiting')
            ->where('client_type', 'regular')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($q) => [
                'formatted_number' => 'R' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                'style_class'      => 'bg-[#2e3192]',
            ]);

        $priorityQueues = (clone $baseQuery)
            ->where('queue_status', 'waiting')
            ->where('client_type', 'priority')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($q) => [
                'formatted_number' => 'P' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                'style_class'      => 'bg-[#ee1c25]',
            ]);

        // 🔹 Pending
        $pendingRegular = (clone $baseQuery)
            ->where('queue_status', 'pending')
            ->where('client_type', 'regular')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($q) => [
                'formatted_number' => 'R' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                'style_class'      => 'bg-[#2e3192]',
            ]);

        $pendingPriority = (clone $baseQuery)
            ->where('queue_status', 'pending')
            ->where('client_type', 'priority')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($q) => [
                'formatted_number' => 'P' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                'style_class'      => 'bg-[#ee1c25]',
            ]);

        // 🔹 Serving (only 1)
        $servingQueue = (clone $baseQuery)
            ->where('queue_status', 'serving')
            ->orderBy('updated_at', 'desc') // latest being served
            ->limit(1)
            ->get()
            ->map(fn($q) => [
                'formatted_number' => ($q->client_type === 'regular'
                    ? 'R' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT)
                    : 'P' . str_pad($q->queue_number, 3, '0', STR_PAD_LEFT)),
                'style_class'      => $q->client_type === 'regular' ? 'bg-[#2e3192]' : 'bg-[#ee1c25]',
            ]);

        return response()->json([
            'upcomingRegu' => $regularQueues,
            'upcomingPrio' => $priorityQueues,
            'pendingRegu'  => $pendingRegular,
            'pendingPrio'  => $pendingPriority,
            'servingQueue' => $servingQueue,
        ]);
    }

}
