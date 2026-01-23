@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            {{-- ✅ Add User Modal --}}
            {{-- <div id="addUserModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
                <div
                    class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800">Add New User</h3>
                        <button id="closeAddUserModal"
                            class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                    </div>
                    <form id="addUserForm" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">First Name</label>
                                <input type="text" name="first_name" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Last Name</label>
                                <input type="text" name="last_name" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="email" name="email" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Position</label>
                                <select name="position" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled selected>-- Select Position --</option>
                                    @foreach (\App\Libraries\Positions::all() as $position)
                                        <option value="{{ $position }}">{{ $position }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Assign Step</label>
                                <select name="step_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled selected>-- Select Step --</option>
                                    @foreach ($users as $step)
                                        <option value="{{ $step->id }}">{{ $step->step_number }} -
                                            {{ $step->step_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Assign Window</label>
                                <select name="window_id" required disabled
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled selected>-- Select Window --</option>
                                </select>
                            </div>
                            @php
                                use App\Libraries\Sections;
                            @endphp
                            @if (auth()->user()->section_id == Sections::CRISIS_INTERVENTION_SECTION())
                                <div id="assignCategoryWrapper">
                                    <label class="block text-sm font-medium text-gray-600">Assign Category</label>
                                    <select id="assignedCategorySelect" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled selected>-- Select Category --</option>
                                        <option value="regular">Regular</option>
                                        <option value="priority">Priority</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>
                            @endif

                            <input type="hidden" id="assignedCategoryHidden" name="assigned_category" value="both">
                        </div>

                        <div x-data="{ show: false }" class="relative">
                            <label for="password" class="block text-sm font-medium text-gray-600">Password</label>

                            <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter password">

                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pt-4 pr-3 text-gray-500 focus:outline-none">

                                <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}"
                                    alt="Show Password" class="h-5 w-5">

                                <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}"
                                    alt="Hide Password" class="h-5 w-5">
                            </button>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelAddUser"
                                class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Cancel</button>
                            <button type="submit"
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Save</button>
                        </div>
                    </form>
                </div>
            </div> --}}

            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Active Users</h2>
                    {{-- <button id="openAddUserModal"
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Add User
                    </button> --}}
                </div>
                <div class="overflow-x-auto flex-1">
                    <table id="usersTable" class="min-w-full divide-y divide-gray-200 text-gray-700">
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
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $u->first_name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $u->last_name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $u->email ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $u->position ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $u->step->step_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $u->window->window_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $u->assigned_category ?? '—' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-3 text-center space-x-2">
                                        <button onclick="deleteUser({{ $u->id }})"
                                            class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($userColumns) + 3 }}" class="px-6 py-3 text-center text-gray-500">
                                        🚫 No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script>
        window.appBaseUrl = "{{ url('') }}";
        window.userColumnsCount = {{ count($userColumns) + 3 }};
    </script> --}}
    @vite('resources/js/adminUsers.js')
@endsection
