@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Active Users</h2>
                </div>

                <!-- Edit User Modal -->
                <div id="editUserModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Edit User</h3>

                        <form id="editUserForm" class="space-y-4">
                            <input type="hidden" id="editUserId">

                            <div>
                                <label class="block text-gray-600 mb-1">Role</label>
                                <select id="editRole" class="w-full border rounded px-3 py-2">
                                    <option value="2">Superadmin</option>
                                    <option value="1">Admin</option>
                                    <option value="0">User</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-600 mb-1">Assigned Step</label>
                                <select id="editStep" class="w-full border rounded px-3 py-2">
                                    <option value="">—</option>
                                    @foreach ($steps as $step)
                                        <option value="{{ $step->id }}">{{ $step->step_number }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-600 mb-1">Assigned Window</label>
                                <select id="editWindow" class="w-full border rounded px-3 py-2">
                                    <option value="">—</option>
                                    @foreach ($windows as $window)
                                        <option value="{{ $window->id }}">{{ $window->window_number }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-600 mb-1">Assigned Category</label>
                                <select id="editCategory" class="w-full border rounded px-3 py-2">
                                    <option value="">—</option>
                                    <option value="regular">Regular</option>
                                    <option value="priority">Priority</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-2 mt-4">
                                <button type="button" onclick="closeModal()"
                                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Save</button>
                            </div>
                        </form>

                        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table id="usersTable" class="min-w-full divide-y divide-gray-200 text-gray-700">
                        <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center rounded-tl-lg">First Name</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center">Last Name</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center">Email Address</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center">Position</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center">Role</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center">Assigned Step</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center">Assigned Window</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center">Assigned Category</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                            @forelse ($users as $u)
                                <tr id="user-row-{{ $u->id }}"
                                    class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200">
                                    <td class="px-6 py-3 text-gray-700 first_name">{{ $u->first_name ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-700 last_name">{{ $u->last_name ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-700 email">{{ $u->email ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-700 position">{{ $u->position ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-700 role">{{ $u->user_type_text }}</td>
                                    <td class="px-6 py-3 text-gray-700 step">{{ $u->step->step_number ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-700 window">{{ $u->window->window_number ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-700 category">{{ $u->assigned_category ?? '—' }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <button
                                            onclick="openEditModal({{ $u->id }}, {{ $u->user_type }}, {{ $u->step_id ?? 'null' }}, {{ $u->window_id ?? 'null' }}, '{{ $u->assigned_category ?? '' }}')"
                                            class="text-white bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 hover:bg-gradient-to-br font-medium rounded-lg text-sm px-5 py-2.5">
                                            Edit
                                        </button>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="{{ count($userColumns) + 3 }}"
                                        class="px-6 py-3 text-center text-gray-500">
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
    <script>
        function openEditModal(userId, role, stepId, windowId, category) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editRole').value = role;
            document.getElementById('editStep').value = stepId ?? '';
            document.getElementById('editWindow').value = windowId ?? '';
            document.getElementById('editCategory').value = category ?? '';

            document.getElementById('editUserModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }

        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const userId = document.getElementById('editUserId').value;
            const role = document.getElementById('editRole').value;
            const step_id = document.getElementById('editStep').value || null;
            const window_id = document.getElementById('editWindow').value || null;
            const assigned_category = document.getElementById('editCategory').value;
            fetch(`${window.appBaseUrl}/admin/active-users/${userId}/update-assignment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        role,
                        step_id,
                        window_id,
                        assigned_category
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update table row dynamically
                        const row = document.getElementById(`user-row-${userId}`);

                        // Map role integer to text
                        const roleText = role == 2 ? 'Superadmin' : role == 1 ? 'Admin' : 'User';
                        row.querySelector('.role').textContent = roleText;
                        row.querySelector('.step').textContent = data.user.step ? data.user.step.step_number :
                            '—';
                        row.querySelector('.window').textContent = data.user.window ? data.user.window
                            .window_number : '—';
                        row.querySelector('.category').textContent = assigned_category || '—';

                        closeModal();
                    } else {
                        alert('Failed to update user.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('An error occurred.');
                });
        });
    </script>
    @vite('resources/js/adminUsers.js')
@endsection
