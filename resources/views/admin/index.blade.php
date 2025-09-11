@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-200">

    <div class="p-4 sm:ml-64">
        {{-- Dashboard cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">

            {{-- Waiting Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Waiting Clients</p>
                <p class="text-2xl font-bold text-blue-600">{{ $waitingCount }}</p>
            </div>

            {{-- Pending Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Pending Clients</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</p>
            </div>

            {{-- Serving Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Serving Clients</p>
                <p class="text-2xl font-bold text-green-600">{{ $servingCount }}</p>
            </div>

            {{-- Priority Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Priority Clients</p>
                <p class="text-2xl font-bold text-red-600">{{ $priorityCount }}</p>
            </div>

            {{-- Regular Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Regular Clients</p>
                <p class="text-2xl font-bold text-gray-800">{{ $regularCount }}</p>
            </div>
        </div>
        {{-- Transactions table --}}
        @include('admin.transactions.table') {{-- Table partial --}}
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

        fetch("{{ route('admin.users.store') }}", {
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

