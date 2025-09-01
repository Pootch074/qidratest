@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]">

    <div class="p-4 sm:ml-64">

        {{-- Dashboard cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            @for ($i = 0; $i < 6; $i++)
                <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50">
                    <p class="text-2xl text-gray-400">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                        </svg>
                    </p>
                </div>
            @endfor
        </div>

        {{-- Transactions table --}}
        <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Transactions</h2>
            @include('admin.transactions.table') {{-- Table partial --}}
        </div>
        <br>
        <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto shadow-md">
            <h2 class="text-xl font-semibold mb-4">Users</h2>
            @include('admin.users.table', ['users' => $users, 'userColumns' => $userColumns])
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
                    <td class="px-6 py-4">${u.step_id || '-'}</td> <!-- ✅ new step_id column -->
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

