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
                                    <td class="px-6 py-3 text-gray-700 text-center step">{{ $u->step->step_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700 text-center window">
                                        {{ $u->window->window_number ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-700 text-center category">
                                        {{ $u->assigned_category ?? '—' }}</td>
                                    <td class="px-6 py-3 text-center space-x-2">
                                        <div class="flex justify-center space-x-2">
                                            @if (!$u->isAdmin())
                                                <button
                                                    onclick="openEditModal({{ $u->id }}, {{ $u->user_type }}, {{ $u->step_id ?? 'null' }}, {{ $u->window_id ?? 'null' }}, '{{ $u->assigned_category ?? '' }}')"
                                                    class="flex-1 text-white bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 hover:from-yellow-500 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 font-medium rounded-lg text-sm px-4 py-2 transition duration-200">
                                                    Edit
                                                </button>
                                                <button onclick="deleteUser({{ $u->id }})"
                                                    class="flex-1 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:from-red-500 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 font-medium rounded-lg text-sm px-4 py-2 transition duration-200">
                                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-3 text-center text-gray-500">
                                        🚫 No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="editUserModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                        <h3 class="text-xl font-semibold mb-4 text-gray-700">Edit User</h3>
                        <form id="editUserForm" class="space-y-4">
                            <input type="hidden" id="editUserId">

                            <!-- Role Dropdown -->
                            <div>
                                <label class="block text-gray-600 mb-1">Role</label>
                                <select id="editRole" name="role" class="w-full border rounded px-3 py-2">
                                    @foreach ($userTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <!-- Step Dropdown -->
                            <div>
                                <label class="block text-gray-600 mb-1">Assigned Step</label>
                                <select id="editStep" class="w-full border rounded px-3 py-2"></select>
                            </div>

                            <!-- Window Dropdown -->
                            <div>
                                <label class="block text-gray-600 mb-1">Assigned Window</label>
                                <select id="editWindow" class="w-full border rounded px-3 py-2">
                                    <option value="">—</option>
                                </select>
                            </div>

                            <!-- Category Dropdown -->
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

                        <button onclick="closeModal()"
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">✕</button>
                    </div>
                </div>
                <!-- Confirmation Modal -->
                <div id="confirmSaveModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 relative">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">Confirm Save</h3>
                        <p class="text-gray-600 mb-6">Are you sure you want to save these changes?</p>
                        <div class="flex justify-end space-x-2">
                            <button id="cancelConfirmBtn"
                                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                            <button id="confirmSaveBtn"
                                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Yes, Save</button>
                        </div>
                        <button onclick="closeConfirmModal()"
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Pass Blade variables to JS
        const userTypesData = @json($userTypes);
        const stepsData = @json($steps);
        const windowsData = @json($windows);
        const currentSectionId = {{ auth()->user()->section_id }};
        let pendingFormData = null;


        // Open Edit Modal
        function openEditModal(userId, role, stepId, windowId, category) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editCategory').value = category ?? '';

            // Populate Roles
            const roleSelect = document.getElementById('editRole');
            roleSelect.innerHTML = '';
            for (const [value, label] of Object.entries(userTypesData)) {
                const opt = document.createElement('option');
                opt.value = value;
                opt.textContent = label;
                roleSelect.appendChild(opt);
            }
            roleSelect.value = role ?? '';

            // Populate Steps
            const stepSelect = document.getElementById('editStep');
            stepSelect.innerHTML = '<option value="">—</option>';
            stepsData.forEach(step => {
                if (step.section_id == currentSectionId) {
                    const opt = document.createElement('option');
                    opt.value = step.id;
                    opt.textContent = step.step_number;
                    stepSelect.appendChild(opt);
                }
            });
            stepSelect.value = stepId ?? '';

            // Populate Windows for selected step
            populateWindows(stepId, windowId);

            // Update windows when step changes
            stepSelect.onchange = function() {
                populateWindows(this.value, null);
            };

            document.getElementById('editUserModal').classList.remove('hidden');
        }

        // Populate Windows dropdown dynamically based on selected step
        function populateWindows(stepId, selectedWindowId) {
            const windowSelect = document.getElementById('editWindow');
            windowSelect.innerHTML = '<option value="">—</option>';

            if (!stepId) return;

            windowsData
                .filter(w => w.step_id == stepId) // only filter by step_id
                .forEach(w => {
                    const opt = document.createElement('option');
                    opt.value = w.id;
                    opt.textContent = w.window_number;
                    windowSelect.appendChild(opt);
                });

            if (selectedWindowId) windowSelect.value = selectedWindowId;
        }


        // Close modal
        function closeModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }

        // Submit form via AJAX
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const userId = document.getElementById('editUserId').value;
            const role = document.getElementById('editRole').value;
            const step_id = document.getElementById('editStep').value || null;
            const window_id = document.getElementById('editWindow').value || null;
            const assigned_category = document.getElementById('editCategory').value;

            pendingFormData = {
                userId,
                role,
                step_id,
                window_id,
                assigned_category
            };
            document.getElementById('confirmSaveModal').classList.remove('hidden');

            fetch(`${window.appBaseUrl}/admin/active-users/${userId}/update-assignment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        role,
                        step_id,
                        window_id,
                        assigned_category
                    })
                })
                .then(async res => {
                    if (!res.ok) throw new Error(await res.text());
                    return res.json();
                })
                .then(data => {
                    const row = document.getElementById(`user-row-${userId}`);
                    row.querySelector('.role').textContent = data.user.user_type_text;
                    row.querySelector('.step').textContent = data.user.step?.step_number ?? '—';
                    row.querySelector('.window').textContent = data.user.window?.window_number ?? '—';
                    row.querySelector('.category').textContent = assigned_category || '—';
                    closeModal();
                })
                .catch(err => {
                    console.error(err);
                    alert('Error: check console for details.');
                });
        });

        // Cancel button
        document.getElementById('cancelConfirmBtn').addEventListener('click', () => {
            pendingFormData = null;
            closeConfirmModal();
        });

        // Confirm button
        document.getElementById('confirmSaveBtn').addEventListener('click', async () => {
            if (!pendingFormData) return;

            const {
                userId,
                role,
                step_id,
                window_id,
                assigned_category
            } = pendingFormData;

            try {
                const res = await fetch(`${window.appBaseUrl}/admin/active-users/${userId}/update-assignment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        role,
                        step_id,
                        window_id,
                        assigned_category
                    })
                });

                if (!res.ok) throw new Error(await res.text());
                const data = await res.json();

                // Update table row
                const row = document.getElementById(`user-row-${userId}`);
                row.querySelector('.role').textContent = data.user.user_type_text;
                row.querySelector('.step').textContent = data.user.step?.step_number ?? '—';
                row.querySelector('.window').textContent = data.user.window?.window_number ?? '—';
                row.querySelector('.category').textContent = assigned_category || '—';

                // Close modals
                closeModal();
                closeConfirmModal();

                pendingFormData = null;
            } catch (err) {
                console.error(err);
                alert('Error: check console for details.');
            }
        });

        function closeConfirmModal() {
            document.getElementById('confirmSaveModal').classList.add('hidden');
        }
    </script>

    @vite('resources/js/adminUsers.js')
@endsection
