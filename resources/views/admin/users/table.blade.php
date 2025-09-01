@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]">
    <div class="p-4 sm:ml-64">

        {{-- Header & Add User --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Users</h2>
            <button id="openAddUserModal" 
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Add User
            </button>
        </div>

        {{-- ✅ Add User Modal --}}
        <div id="addUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800">Add New User</h3>
            <button id="closeAddUserModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        </div>

        <form id="addUserForm" class="space-y-5">
            @csrf

            {{-- First & Last Name --}}
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

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" name="email" required 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Position & User Type --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Position</label>
                    <input type="text" name="position" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">User Type</label>
                    <select name="user_type" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select --</option>
                        <option value="1">Admin</option>
                        <option value="6">User</option>
                    </select>
                </div>
            </div>

            {{-- Assign Category & Step --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Assign Category</label>
                    <select name="assigned_category" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select --</option>
                        <option value="regular">Regular</option>
                        <option value="priority">Priority</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Assign Step</label>
                    <select name="step_id" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Step --</option>
                        @foreach($steps as $step)
                            <option value="{{ $step->id }}">{{ $step->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Assign Window --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">Assign Window</label>
                <select name="window_id" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Window --</option>
                    @foreach($windows as $window)
                        <option value="{{ $window->id }}">{{ $window->window_number }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter password">
            </div>


            {{-- Buttons --}}
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelAddUser" 
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="submit" 
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Save</button>
            </div>
        </form>
    </div>
</div>

        {{-- Users Table --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table id="usersTable" class="w-full text-sm text-left text-gray-500">
<thead>
    <tr>
        @foreach($userColumns as $label)
            <th class="px-6 py-3">{{ $label }}</th>
        @endforeach
        <th class="px-6 py-3">Assigned Step</th>
        <th class="px-6 py-3">Assigned Window</th>
        <th class="px-6 py-3">Actions</th>
    </tr>
</thead>

                <tbody>
                    @forelse ($users as $u)
                    <tr>
                        @foreach($userColumns as $field => $label)
                            <td class="px-6 py-4">{{ $u->$field ?? '—' }}</td>
                        @endforeach
                        <td class="px-6 py-4">{{ $u->step->step_number ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $u->window->window_number ?? '—' }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="#" class="text-green-600 hover:underline">Edit</a>
                            <button onclick="deleteUser({{ $u->id }})" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>

                    @empty
                        <tr>
                            <td colspan="{{ count($userColumns)+2 }}" class="px-6 py-4 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addUserModal');
    const openBtn = document.getElementById('openAddUserModal');
    const closeBtn = document.getElementById('closeAddUserModal');
    const cancelBtn = document.getElementById('cancelAddUser');

    // Open modal with animation
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        setTimeout(() => modal.firstElementChild.classList.remove('scale-95'), 10);
    });

    // Close modal
    const closeModal = () => {
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    };
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Add user AJAX
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("{{ route('admin.store') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                const u = data.user;
                const row = `
                <tr id="userRow-${u.id}" class="bg-white border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4">${u.first_name}</td>
                    <td class="px-6 py-4">${u.last_name}</td>
                    <td class="px-6 py-4">${u.email}</td>
                    <td class="px-6 py-4">${u.position}</td>
                    <td class="px-6 py-4">${u.user_type}</td>
                    <td class="px-6 py-4">${u.assigned_category}</td>
                    <td class="px-6 py-4">${u.window_id}</td>
                    <td class="px-6 py-4">${u.step?.name ?? '—'}</td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="#" class="text-green-600 hover:underline">Edit</a>
                        <button onclick="deleteUser(${u.id})" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>`;
                document.querySelector('#usersTable tbody').insertAdjacentHTML('beforeend', row);
                closeModal();
                this.reset();
            } else alert('Error: ' + data.message);
        })
        .catch(err => console.error(err));
    });
});
</script>
@endsection
