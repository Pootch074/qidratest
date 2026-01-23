@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            {{-- ✅ Add User Modal --}}
            <div id="addUserModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
                <div
                    class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800">Add New User</h3>
                        <button id="closeAddUserModal"
                            class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Pending Users</h2>
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
                                    <td class="px-6 py-3 text-center space-x-2">
                                        <button onclick="deleteUser({{ $u->id }})"
                                            class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                                            <i class="fas fa-trash-alt"></i> Approve
                                        </button>
                                    </td>
                                </tr>
                            @empty
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/adminUsers.js')
@endsection
