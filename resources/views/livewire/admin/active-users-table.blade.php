<div>
    @if (session()->has('message'))
        <div class="mb-2 text-green-600">{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-2 text-red-600">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto flex-1">
        <table class="min-w-full divide-y divide-gray-200 text-gray-700">
            <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 font-semibold tracking-wide text-center rounded-tl-lg">First Name</th>
                    <th class="px-6 py-3 font-semibold tracking-wide text-center">Last Name</th>
                    <th class="px-6 py-3 font-semibold tracking-wide text-center">Email Address</th>
                    <th class="px-6 py-3 font-semibold tracking-wide text-center">Position</th>
                    <th class="px-6 py-3 font-semibold tracking-wide text-center">Assigned Step</th>
                    <th class="px-6 py-3 font-semibold tracking-wide text-center">Assigned Window</th>
                    <th class="px-6 py-3 font-semibold tracking-wide text-center">Assigned Category</th>
                    <th class="px-6 py-3 font-semibold tracking-wide text-center rounded-tr-lg">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                @forelse ($users as $u)
                    <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200">
                        <td class="px-6 py-3 text-gray-700">{{ $u->first_name ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $u->last_name ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $u->email ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $u->position ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $u->step->step_number ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $u->window->window_number ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $u->assigned_category ?? '—' }}</td>
                        <td class="px-6 py-3 text-center space-x-2">
                            <button wire:click="deleteUser({{ $u->id }})"
                                class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-3 text-center text-gray-500">🚫 No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
