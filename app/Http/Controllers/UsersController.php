<?php

namespace App\Http\Controllers;

use App\Enums\ClientType;
use App\Http\Requests\ValidateUserQueueRequest;
use App\Libraries\Sections;
use App\Libraries\Steps;
use App\Models\Step;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Window;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    private function getQueuesForUser($user)
    {
        $sectionId = $user->section_id;

        $formatQueues = function ($queues) {
            return $queues->map(function ($q) {
                $clientType = $q->client_type instanceof \App\Enums\ClientType
                    ? $q->client_type->value
                    : $q->client_type;

                $q->formatted_number = strtoupper(substr($clientType, 0, 1))
                    .str_pad($q->queue_number, 3, '0', STR_PAD_LEFT);

                $q->style_class = strtolower($clientType) === 'priority'
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
        logger()->info('Logged-in user step_id: '.$user->step_id);

        $queues = $this->getQueuesForUser($user);

        $stepNumber = optional($user->step)->step_number;
        $windowNumber = optional($user->window)->window_number;
        $fieldOffice = optional($user->section?->division?->office)->field_office;
        $divisionName = optional($user->section?->division)->division_name;
        $sectionName = optional($user->section)->section_name;

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
            'stepNumber' => optional($user->step)->step_number ?? 'N/A',
            'windowNumber' => optional($user->window)->window_number ?? 'N/A',
            'sectionName' => optional($user->section)->section_name ?? 'N/A',
            'divisionName' => optional($user->section?->division)->division_name ?? 'N/A',
            'fieldOffice' => optional($user->section?->division?->office)->field_office ?? 'N/A',
        ];

        return response()->json(array_merge($queues, [
            'userInfo' => $userInfo,
        ]));
    }

    public function admin()
    {
        $user = Auth::user();
        $sectionId = $user->section_id;

        // ✅ Transaction counts filtered by user's section and only for today
        $waitingCount = Transaction::where('queue_status', 'waiting')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $pendingCount = Transaction::where('queue_status', 'pending')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $servingCount = Transaction::where('queue_status', 'serving')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $priorityCount = Transaction::where('client_type', 'priority')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $regularCount = Transaction::where('client_type', 'regular')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $returneeCount = Transaction::where('client_type', 'deferred')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        $completedCount = Transaction::where('queue_status', 'completed')
            ->where('section_id', $sectionId)
            ->whereDate('created_at', now())
            ->count();

        // ✅ Users in the same section
        $userColumns = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'position' => 'Position',
            'user_type' => 'User Type',
            'assigned_category' => 'Category',
            'window_id' => 'Window ID',
        ];

        $users = User::where('section_id', $sectionId)
            ->latest()
            ->get();

        // ✅ Transactions filtered by section, only today, ordered by latest queue_number
        $transactions = Transaction::where('section_id', $sectionId)
            ->whereDate('created_at', now())
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
            'returneeCount',
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'position' => 'Position',
            'user_type' => 'User Type',
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
            ->where('user_type', '!=', 1) // ✅ exclude type 1
            ->orderBy('id', 'asc')
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

        // 🟢 Validation rules
        $rules = [
            'first_name' => 'required|string|max:255|regex:/^[A-Za-z\s\'-]+$/',
            'last_name' => 'required|string|max:255|regex:/^[A-Za-z\s\'-]+$/',
            'email' => 'required|email|unique:users,email',
            'position' => 'required|string|max:255',
            // 👇 allow "both" only if section_id == 15
            'assigned_category' => $sectionId == 15
                ? 'required|string|in:regular,priority,both'
                : 'nullable|string',
            'step_id' => 'required|exists:steps,id',
            'window_id' => 'required|exists:windows,id',
            'password' => [
                'required',
                'string',
                'min:12',                 // ✅ at least 12 chars
                'regex:/[a-z]/',          // ✅ at least 1 lowercase
                'regex:/[A-Z]/',          // ✅ at least 1 uppercase
                'regex:/[0-9]/',          // ✅ at least 1 number
                'regex:/[@$!%*?&]/',      // ✅ at least 1 special char
            ],
        ];

        // 🟡 Custom error messages
        $messages = [
            'first_name.regex' => 'First name may only contain letters, spaces, apostrophes, or hyphens.',
            'last_name.regex' => 'Last name may only contain letters, spaces, apostrophes, or hyphens.',
            'password.min' => 'Password must be at least 12 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@ $ ! % * ? &).',
        ];

        $validated = $request->validate($rules, $messages);

        // 🛡️ Force defaults
        $validated['user_type'] = 5; // always TYPE_USER

        if ($sectionId != 15) {
            // 🔒 override regardless of input
            $validated['assigned_category'] = 'both';
        }

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'position' => $validated['position'],
            'user_type' => $validated['user_type'],
            'assigned_category' => $validated['assigned_category'],
            'step_id' => $validated['step_id'],
            'window_id' => $validated['window_id'] ?? null,
            'section_id' => $sectionId,
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'position' => $user->position,
                'user_type_name' => $user->getUserTypeName(),
                'assigned_category' => $user->assigned_category,
                'window_number' => $user->window->window_number ?? null,
                'step_number' => $user->step->step_number ?? null,
            ],
        ]);
    }

    public function destroy(User $user)
    {
        try {
            $user->forceDelete(); // ⚡️ this will run DELETE FROM users WHERE id=?

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function nextRegular(ValidateUserQueueRequest $_request)
    {
        $user = Auth::user();

        $transaction = DB::transaction(function () use ($user) {
            $query = Transaction::where('queue_status', 'waiting')
                ->where('client_type', 'regular')
                ->where('step_id', $user->step_id)
                ->where('section_id', $user->section_id)
                ->whereDate('updated_at', Carbon::today())
                ->lockForUpdate()
                ->orderBy('queue_number', 'asc');

            $preAssessSectionId = Sections::PREASSESSMENT_SECTION();
            $preAssessSteps = [Steps::PRE_ASSESSMENT(), Steps::ENCODING()];

            if ($user->section_id === $preAssessSectionId && in_array($user->step->step_number, $preAssessSteps)) {
                $category = $user->assigned_category instanceof \App\Enums\ClientType
                    ? $user->assigned_category->value
                    : $user->assigned_category;

                $query->whereIn('client_type', [$category, 'deferred']);
            }

            $record = $query->first();

            if ($record) {
                $record->update([
                    'queue_status' => 'serving',
                    'window_id' => $user->window_id,
                ]);
            }

            return $record;
        });

        if ($transaction) {
            $clientType = $transaction->client_type instanceof \App\Enums\ClientType
                ? $transaction->client_type->value
                : $transaction->client_type;

            return response()->json([
                'status' => 'success',
                'transaction' => $transaction,
                'message' => "Serving client {$clientType}{$transaction->queue_number} at window {$user->window_id}",
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'No regular clients waiting in the queue.',
        ]);
    }

    public function nextPriority()
    {
        $user = Auth::user();

        if (! $user->step_id || ! $user->section_id || ! $user->window_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not assigned to a step, section, or window.',
            ], 400);
        }

        $transaction = DB::transaction(function () use ($user) {
            $record = Transaction::where('queue_status', 'waiting')
                ->where('client_type', 'priority')
                ->where('step_id', $user->step_id)
                ->where('section_id', $user->section_id)
                ->whereDate('updated_at', Carbon::today())
                ->lockForUpdate()
                ->orderBy('queue_number', 'asc')
                ->first();

            if ($record) {
                $record->update([
                    'queue_status' => 'serving',
                    'window_id' => $user->window_id,
                ]);
            }

            return $record;
        });

        if ($transaction) {
            return response()->json([
                'status' => 'success',
                // 'message' => "Serving PRIORITY client {$transaction->client_type}{$transaction->queue_number} at window {$user->window_id}",
                'transaction' => $transaction,
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'No priority clients waiting in the queue.',
        ]);
    }

    public function nextReturnee()
    {
        $user = Auth::user();

        if (! $user->step_id || ! $user->section_id || ! $user->window_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not assigned to a step, section, or window.',
            ], 400);
        }

        $transaction = DB::transaction(function () use ($user) {
            $record = Transaction::where('queue_status', 'waiting')
                ->where('client_type', 'deferred')
                ->where('step_id', $user->step_id)
                ->where('section_id', $user->section_id)
                // ->where('window_id', $user->window_id)
                ->whereDate('updated_at', Carbon::today())
                ->lockForUpdate()
                ->orderBy('queue_number', 'asc')
                ->first();

            if ($record) {
                $record->update([
                    'queue_status' => 'serving',
                    'window_id' => $user->window_id,
                ]);
            }

            return $record;
        });

        if ($transaction) {
            return response()->json([
                'status' => 'success',
                // 'message' => "Serving RETURNEE client {$transaction->client_type}{$transaction->queue_number} at window {$user->window_id}",
                'transaction' => $transaction,
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'No priority clients waiting in the queue.',
        ]);
    }

    public function skipQueue()
    {
        $user = Auth::user();

        if (! $user->step_id || ! $user->section_id || ! $user->window_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not assigned to a step, section, or window.',
            ], 400);
        }

        $transaction = DB::transaction(function () use ($user) {
            $query = Transaction::where('queue_status', 'serving')
                ->where('section_id', $user->section_id)
                ->where('step_id', $user->step_id)
                ->where('window_id', $user->window_id)
                ->whereDate('updated_at', Carbon::today())
                ->lockForUpdate();

            // ✅ Replace magic numbers with descriptive constants
            $preAssessSteps = [Steps::PRE_ASSESSMENT(), Steps::ENCODING()];
            $preAssessSectionId = Sections::CRISIS_INTERVENTION_SECTION(); // your cached getter

            if ($user->section_id === $preAssessSectionId && in_array($user->step->step_number, $preAssessSteps)) {
                $category = $user->assigned_category instanceof \App\Enums\ClientType
                    ? $user->assigned_category->value
                    : $user->assigned_category;

                $query->whereIn('client_type', [$category, 'deferred']);
            }

            $current = $query->first();

            if (! $current) {
                return null;
            }

            $current->update([
                'queue_status' => 'pending',
                'window_id' => null,
            ]);

            return $current;
        });

        if ($transaction) {
            $clientType = $transaction->client_type instanceof \App\Enums\ClientType
                ? $transaction->client_type->value
                : $transaction->client_type;

            return response()->json([
                'status' => 'success',
                'message' => "Queue {$clientType}{$transaction->queue_number} has been skipped.",
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'No active serving queue to skip.',
        ]);
    }

    public function recallQueue()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Start query for currently serving transaction
        $query = Transaction::where('queue_status', 'serving')
            ->whereDate('created_at', $today)
            ->whereDate('updated_at', $today)
            ->where('ticket_status', 'issued')
            ->where('section_id', $user->section_id)
            ->where('step_id', $user->step_id)
            ->where('window_id', $user->window_id);

        // ✅ Apply client_type filter only if section_id = 15 AND step_number is 1 or 2
        if ($user->section_id == 15 && in_array($user->step->step_number, [1, 2])) {
            $query->whereIn('client_type', [$user->assigned_category, 'deferred']);
        }

        $transaction = $query->first();

        if (! $transaction) {
            return response()->json([
                'message' => 'No serving queue found for today.',
            ], 404);
        }

        // Increment recall_count
        $transaction->recall_count = $transaction->recall_count ? $transaction->recall_count + 1 : 1;
        $transaction->save();

        return response()->json([
            'message' => 'Queue recalled successfully.',
            'recall_count' => $transaction->recall_count,
            'queue_id' => $transaction->id,
        ]);
    }

    public function proceedQueue()
    {
        $user = Auth::user();

        if (! $user->step_id || ! $user->section_id || ! $user->window_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not assigned to a step, section, or window.',
            ], 400);
        }

        $transaction = DB::transaction(function () use ($user) {
            // Start query for currently serving transaction
            $currentQuery = Transaction::where('queue_status', 'serving')
                ->where('section_id', $user->section_id)
                ->where('step_id', $user->step_id)
                ->where('window_id', $user->window_id)
                ->whereDate('updated_at', Carbon::today());

            // ✅ Include deferred clients for regular/priority users
            if ($user->section_id == 15 && in_array($user->step->step_number, [1, 2])) {
                $currentQuery->whereIn('client_type', [$user->assigned_category, 'deferred']);
            }

            $current = $currentQuery->lockForUpdate()->first();

            if (! $current) {
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
                    'step_id' => $nextStep->id,
                    'recall_count' => null,
                    'queue_status' => 'waiting',
                    'window_id' => null,
                ]);
            } else {
                // ✅ No next step -> mark as completed
                $current->update([
                    'queue_status' => 'completed',
                    'window_id' => null,
                ]);
            }

            return $current;
        });

        if ($transaction) {
            return response()->json([
                'status' => 'success',
                'message' => $transaction->queue_status === 'completed'
                    ? 'Queue has been completed.'
                    : 'Queue successfully proceeded to the next step.',
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'No active serving queue to proceed.',
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
        if (! Auth::check()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'redirect' => route('login'),
            ], 401);
        }

        $user = Auth::user();
        $baseQuery = Transaction::where('section_id', $user->section_id)
            ->where('step_id', $user->step_id)
            ->whereDate('created_at', Carbon::today());

        $formatQueue = fn ($queues, $type) => $queues->map(fn ($q) => [
            'id' => $q->id,
            'formatted_number' => match ($type) {
                ClientType::REGULAR => 'R'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                ClientType::PRIORITY => 'P'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                ClientType::DEFERRED => 'D'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                default => 'X'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
            },
            'style_class' => match ($type) {
                ClientType::REGULAR => 'bg-[#2e3192]',
                ClientType::PRIORITY => 'bg-[#ee1c25]',
                ClientType::DEFERRED => 'bg-[#f97316]',
                default => 'bg-gray-500',
            },
        ]);

        $regularQueues = $formatQueue(
            (clone $baseQuery)->where('queue_status', 'waiting')->where('client_type', ClientType::REGULAR)->orderBy('queue_number')->get(),
            ClientType::REGULAR
        );

        $priorityQueues = $formatQueue(
            (clone $baseQuery)->where('queue_status', 'waiting')->where('client_type', ClientType::PRIORITY)->orderBy('queue_number')->get(),
            ClientType::PRIORITY
        );

        $returneeQueues = $formatQueue(
            (clone $baseQuery)->where('queue_status', 'waiting')->where('client_type', ClientType::DEFERRED)->where('ticket_status', 'issued')->orderBy('queue_number')->get(),
            ClientType::DEFERRED
        );

        // 🔹 Pending
        $pendingRegular = $formatQueue(
            (clone $baseQuery)->where('queue_status', 'pending')->where('client_type', ClientType::REGULAR)->orderBy('queue_number')->get(),
            ClientType::REGULAR
        );

        $pendingPriority = $formatQueue(
            (clone $baseQuery)->where('queue_status', 'pending')->where('client_type', ClientType::PRIORITY)->orderBy('queue_number')->get(),
            ClientType::PRIORITY
        );

        $pendingReturnee = $formatQueue(
            (clone $baseQuery)->where('queue_status', 'pending')->where('client_type', ClientType::DEFERRED)->orderBy('queue_number')->get(),
            ClientType::DEFERRED
        );

        $deferred = (clone $baseQuery)
            ->where('queue_status', 'deferred')
            ->where('ticket_status', 'issued')
            ->where('window_id', $user->window_id)
            ->orderBy('queue_number', 'asc')
            ->get()
            ->map(fn ($q) => [
                'formatted_number' => match ($q->client_type) {
                    ClientType::REGULAR => 'R'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                    ClientType::PRIORITY => 'P'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                    ClientType::DEFERRED => 'D'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                    default => 'X'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                },
                'style_class' => 'bg-gray-500',
            ]

            );

        $servingQuery = (clone $baseQuery)
            ->where('queue_status', 'serving')
            ->where('window_id', $user->window_id);

        if ($user->section_id == 15 && in_array(optional($user->step)->step_number, [1, 2])) {
            $servingQuery->whereIn('client_type', [$user->assigned_category, ClientType::DEFERRED]);
        }

        $servingQueue = $servingQuery
            ->orderBy('updated_at', 'desc') // latest being served
            ->limit(1)
            ->get()
            ->map(fn ($q) => [
                'formatted_number' => match ($q->client_type) {
                    ClientType::REGULAR, 'both' => 'R'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                    ClientType::PRIORITY => 'P'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                    ClientType::DEFERRED => 'D'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                    default => 'X'.str_pad($q->queue_number, 3, '0', STR_PAD_LEFT),
                },
                'style_class' => match ($q->client_type) {
                    ClientType::REGULAR, 'both' => 'bg-[#2e3192] text-8xl h-full flex items-center justify-center',
                    ClientType::PRIORITY => 'bg-[#ee1c25] text-8xl h-full flex items-center justify-center',
                    ClientType::DEFERRED => 'bg-[#f97316] text-8xl h-full flex items-center justify-center',
                    default => 'bg-gray-500',
                },
            ]);

        return response()->json([
            'upcomingRegu' => $regularQueues,
            'upcomingPrio' => $priorityQueues,
            'upcomingReturnee' => $returneeQueues,
            'pendingRegu' => $pendingRegular,
            'pendingPrio' => $pendingPriority,
            'pendingReturnee' => $pendingReturnee,
            'deferred' => $deferred,
            'servingQueue' => $servingQueue,
        ]);
    }

    public function returnQueue()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Base query
        $query = Transaction::where('section_id', $user->section_id)
            ->where('step_id', $user->step_id)
            ->where('window_id', $user->window_id)
            ->where('ticket_status', 'issued')
            ->where('queue_status', 'serving')
            ->whereDate('updated_at', $today);

        // ✅ Apply client_type filter only when section_id = 15 and step_number = 1 or 2
        if ($user->section_id == 15 && in_array($user->step->step_number, [1, 2])) {
            $query->where('client_type', $user->assigned_category);
        }

        $transaction = $query->first();

        if (! $transaction) {
            return response()->json([
                'success' => false,
                'message' => 'No active serving transaction found.',
            ]);
        }

        $transaction->update([
            'queue_status' => 'deferred',
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction marked as returnee.',
        ]);
    }

    private function updatePendingTransaction(Request $request, string $clientType)
    {
        $user = Auth::user();
        $sectionId = $user->section_id;

        $transaction = Transaction::where('id', $request->id)
            ->where('section_id', $sectionId)
            ->where('queue_status', 'pending')
            ->where('client_type', $clientType)
            ->first();

        if (! $transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found.'], 404);
        }

        // Find the next step
        $nextStep = Step::where('section_id', $sectionId)
            ->where('step_number', '>', $transaction->step->step_number)
            ->orderBy('step_number', 'asc')
            ->first();

        if ($nextStep) {
            // Move to next step
            $transaction->step_id = $nextStep->id;
            $transaction->queue_status = 'waiting';
            $transaction->window_id = null;
            $transaction->recall_count = null;
            $message = 'Transaction moved to the next step.';
        } else {
            // No more steps → completed
            $transaction->queue_status = 'completed';
            $transaction->window_id = $user->window_id; // last handler
            $message = 'Transaction completed (no more steps).';
        }

        $transaction->save();

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function updatePendingRegu(Request $request)
    {
        return $this->updatePendingTransaction($request, 'regular');
    }

    public function updatePendingPrio(Request $request)
    {
        return $this->updatePendingTransaction($request, 'priority');
    }

    public function updatePendingReturnee(Request $request)
    {
        return $this->updatePendingTransaction($request, 'deferred');
    }

    public function serveAgain(Request $request)
    {
        $queue = Transaction::findOrFail($request->id);

        // ✅ Set the window and status
        $queue->window_id = $request->window_id;
        $queue->queue_status = $request->queue_status;

        // ✅ Increment recall_count
        $queue->recall_count = ($queue->recall_count ?? 0) + 1;

        $queue->save();

        return response()->json([
            'message' => 'Client set to serving again.',
            'recall_count' => $queue->recall_count,
        ]);
    }

    public function updateUpcoming(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
            ]);

            $user = Auth::user();
            if (! $user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $transaction = \App\Models\Transaction::find($request->id);
            if (! $transaction) {
                return response()->json(['message' => 'Queue not found'], 404);
            }

            $transaction->update([
                'window_id' => $user->window_id,
                'queue_status' => 'serving',
            ]);

            return response()->json(['message' => 'Queue updated successfully'], 200);
        } catch (\Throwable $e) {
            \Log::error('updateUpcoming error: '.$e->getMessage());

            return response()->json(['message' => 'Server error: '.$e->getMessage()], 500);
        }
    }

    public function updateUpcomingPreassessRegu(Request $request)
    {
        $user = Auth::user();
        $sectionId = $user->section_id;

        $transaction = Transaction::where('id', $request->id)
            ->where('section_id', $sectionId)
            ->where('queue_status', 'waiting')
            ->where('client_type', $user->assigned_category) // you already have the user
            ->first();

        if (! $transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found.'], 404);
        }

        $nextStep = Step::where('section_id', $sectionId)
            ->where('step_number', '>', $transaction->step->step_number)
            ->orderBy('step_number', 'asc')
            ->first();

        if ($nextStep) {
            $transaction->step_id = $nextStep->id;
            $transaction->queue_status = 'waiting';
            $transaction->window_id = null;
            $transaction->recall_count = null;
            $message = 'Transaction moved to the next step.';
        } else {
            $transaction->queue_status = 'completed';
            $transaction->window_id = $user->window_id;
            $message = 'Transaction completed (no more steps).';
        }

        $transaction->save();

        return response()->json(['success' => true, 'message' => $message]);
    }
}
