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
            <button id="openAddUserModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Add User
            </button>
        </div>

        {{-- Users Table --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table id="usersTable" class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">First Name</th>
                        <th class="px-6 py-3">Last Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Position</th>
                        <th class="px-6 py-3">User Type</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Window</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr id="userRow-{{ $user->id }}" class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $user->first_name }}</td>
                        <td class="px-6 py-4">{{ $user->last_name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->position ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->user_type }}</td>
                        <td class="px-6 py-4">{{ $user->assigned_category ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->window_id ?? '-' }}</td>
<td class="px-6 py-4 space-x-2">
    <a href="#" class="font-medium text-green-600 hover:underline">Edit</a>
    <button 
        class="font-medium text-red-600 hover:underline deleteBtn" 
        data-user-id="{{ $user->id }}">
        Delete
    </button>
</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Add User Modal --}}
        <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded shadow w-1/3">
                <h3 class="text-lg font-semibold mb-4">Add New User</h3>
                <form id="addUserForm">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>First Name</label>
                            <input type="text" name="first_name" class="w-full border px-2 py-1 rounded" required>
                        </div>
                        <div>
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="w-full border px-2 py-1 rounded" required>
                        </div>
                        <div>
                            <label>Email</label>
                            <input type="email" name="email" class="w-full border px-2 py-1 rounded" required>
                        </div>
                        <div>
                            <label>Password</label>
                            <input type="password" name="password" class="w-full border px-2 py-1 rounded" required>
                        </div>
                        <div>
                            <label>Position</label>
                            <input type="text" name="position" class="w-full border px-2 py-1 rounded">
                        </div>
                        <div>
                            <label>User Type</label>
                            <input type="number" name="user_type" class="w-full border px-2 py-1 rounded" value="0" min="0">
                        </div>
                        <div>
                            <label>Category</label>
                            <select name="assigned_category" class="w-full border px-2 py-1 rounded">
                                <option value="">Select</option>
                                <option value="regular">Regular</option>
                                <option value="priority">Priority</option>
                            </select>
                        </div>
                        <div>
                            <label>Window ID</label>
                            <input type="number" name="window_id" class="w-full border px-2 py-1 rounded">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // Modal toggle
    const modal = document.getElementById('addUserModal');
    document.getElementById('openAddUserModal').addEventListener('click', () => modal.classList.remove('hidden'));
    document.getElementById('closeModal').addEventListener('click', () => modal.classList.add('hidden'));

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
                <tr id="userRow-${u.id}" class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">${u.first_name}</td>
                    <td class="px-6 py-4">${u.last_name}</td>
                    <td class="px-6 py-4">${u.email}</td>
                    <td class="px-6 py-4">${u.position || '-'}</td>
                    <td class="px-6 py-4">${u.user_type}</td>
                    <td class="px-6 py-4">${u.assigned_category || '-'}</td>
                    <td class="px-6 py-4">${u.window_id || '-'}</td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="#" class="font-medium text-green-600 hover:underline">Edit</a>
                        <button onclick="deleteUser(${u.id})" class="font-medium text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>`;
                document.querySelector('#usersTable tbody').insertAdjacentHTML('beforeend', row);
                modal.classList.add('hidden');
                this.reset();
            } else alert('Error: ' + data.message);
        })
        .catch(err => console.error(err));
    });




</script>
@endsection
