@extends('layouts.superadmin')
@section('title', 'SUPER ADMIN')
@section('header')
@endsection

@section('content')
<div class="p-4 sm:ml-64">
    <div class="max-w-6xl mx-auto">

        <!-- Page Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Admin Users</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search & Filter Bar -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <form method="GET" action="{{ route('superadmin') }}" class="flex flex-1 gap-3">
                <!-- Search -->
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or email..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:ring-indigo-500 transition">
                </div>

                <!-- Filter by Section -->
                <div>
                    <select name="section"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:ring-indigo-500 transition">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    Search
                </button>
            </form>

            <!-- Reset Button -->
            @if(request('search') || request('section'))
                <a href="{{ route('superadmin') }}"
                   class="text-sm text-gray-600 hover:text-indigo-600 transition">
                   Reset
                </a>
            @endif
        </div>

        <!-- Admin Users Table -->
        <div class="overflow-x-auto bg-white shadow rounded-xl">
                <table class="w-full border-collapse text-left">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">First Name</th>
                        <th class="px-6 py-4">Last Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Section</th>
                        <th class="px-6 py-4">Position</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse($admins as $index => $admin)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm">{{ $admin->first_name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $admin->last_name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $admin->email }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $admin->section ? $admin->section->section_name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $admin->position ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-gray-500 text-sm">
                                No admin users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Divider -->
        <div class="my-12 border-t border-gray-200"></div>

        <!-- Add Admin Form -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Add New Admin User</h2>

        <form action="{{ route('superadmin.store') }}" method="POST" class="space-y-6 bg-white p-8 rounded-xl shadow">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3"
                    required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3"
                    required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                <input type="text" name="section" value="{{ old('section') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3"
                    required>
                @error('section')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3"
                    required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 px-3"
                    required>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    Add Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
