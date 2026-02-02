@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Pending Users</h2>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table id="pendingUsersTable" class="min-w-full divide-y divide-gray-200 text-gray-700">
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
                                        {{ $u->user_type_text }}
                                    </td>
                                    <td class="px-6 py-3 text-center text-gray-700">
                                        {{ $u->step->step_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-center text-gray-700">
                                        {{ $u->window->window_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-center text-gray-700">
                                        {{ $u->assigned_category ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-center space-x-2">
                                        <button onclick="openEditUserModal({{ $u->id }}, {{ $u->user_type ?? 5 }})"
                                            class="flex-1 text-white bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 hover:from-yellow-500 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 font-medium rounded-lg text-sm px-4 py-2 transition duration-200">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                    </td>
                                </tr>
                            @empty
                            @endforelse

                        </tbody>
                    </table>
                </div>
                {{-- Edit User Modal --}}
                <div id="editUserModal"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
                    <div
                        class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 transform transition-transform duration-300 scale-95">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-semibold text-gray-800">Edit User Details</h3>
                            <button id="closeEditUserModal"
                                class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                        </div>
                        <form id="editUserForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="user_id" id="editUserId">
                            <div class="mb-4">
                                <label for="user_role" class="block text-gray-700 font-medium mb-2">Role</label>
                                <select name="user_role" id="editUserType" required
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled>Select Type</option>
                                    @foreach ($usertypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="user_step" class="block text-gray-700 font-medium mb-2">Step</label>
                                <select id="stepSelect" required
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled>Select Step</option>
                                    @foreach ($steps as $step)
                                        <option value="{{ $step->id }}">
                                            Step {{ $step->step_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="user_window" class="block text-gray-700 font-medium mb-2">Window</label>
                                <select id="windowSelect" required
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none"
                                    disabled>
                                    <option value="" disabled>Select Window</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="user_category" class="block text-gray-700 font-medium mb-2">Category</label>
                                <select name="user_category" id="editUserCategory" required
                                    class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                                    <option value="" disabled selected>Select Category</option>
                                    <option value="regular">Regular</option>
                                    <option value="priority">Priority</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" id="cancelEditUser"
                                    class="px-5 py-2.5 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
                                <button type="submit"
                                    class="px-5 py-2.5 bg-[#2e3192] text-white rounded-lg hover:bg-indigo-700">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const editModal = document.getElementById('editUserModal');
        const closeEditModalBtn = document.getElementById('closeEditUserModal');
        const cancelEditBtn = document.getElementById('cancelEditUser');
        const editUserForm = document.getElementById('editUserForm');
        const editUserIdInput = document.getElementById('editUserId');
        const editUserTypeSelect = document.getElementById('editUserType');

        function openEditUserModal(userId, userType) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserType').value = userType;
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        document.getElementById('closeEditUserModal').addEventListener('click', () => {
            document.getElementById('editUserModal').classList.add('hidden');
        });

        document.getElementById('cancelEditUser').addEventListener('click', () => {
            document.getElementById('editUserModal').classList.add('hidden');
        });

        closeEditModalBtn.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });

        cancelEditBtn.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });

        // Optional: submit via AJAX (otherwise, normal form submission)
        editUserForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const userId = editUserIdInput.value;
            const userType = editUserTypeSelect.value;
            fetch(`{{ url('admin/pending-users/users') }}/${userId}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_type: userType
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('User type updated!');
                        location.reload(); // refresh table
                    } else {
                        alert('Failed to update user type.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error occurred.');
                });
        });

        document.getElementById('stepSelect').addEventListener('change', function() {
            const stepId = this.value;
            const windowSelect = document.getElementById('windowSelect');

            windowSelect.innerHTML = '<option value="">Loading...</option>';
            windowSelect.disabled = true;

            if (!stepId) {
                windowSelect.innerHTML = '<option value="">Select Window</option>';
                return;
            }
            fetch(`{{ url('admin/pending-users/steps') }}/${stepId}/windows`)
                .then(res => res.json())
                .then(data => {
                    windowSelect.innerHTML = '<option value="">Select Window</option>';

                    data.windows.forEach(win => {
                        const option = document.createElement('option');
                        option.value = win.id;
                        option.textContent = `Window ${win.window_number}`;
                        windowSelect.appendChild(option);
                    });

                    windowSelect.disabled = false;
                })
                .catch(() => {
                    windowSelect.innerHTML = '<option value="">Error loading windows</option>';
                });
        });
    </script>

    @vite('resources/js/adminUsers.js')
@endsection
