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
  <select 
    name="position" 
    required 
    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
  >
    <option value="" disabled selected>-- Select Position --</option>
    <option value="Accountant III">Accountant III</option>
    <option value="Administrative Aide I">Administrative Aide I</option>
    <option value="Administrative Aide II">Administrative Aide II</option>
    <option value="Administrative Aide III">Administrative Aide III</option>
    <option value="Administrative Aide IV">Administrative Aide IV</option>
    <option value="Administrative Aide V">Administrative Aide V</option>
    <option value="Administrative Aide VI">Administrative Aide VI</option>
    <option value="Administrative Assistant I">Administrative Assistant I</option>
    <option value="Administrative Assistant II">Administrative Assistant II</option>
    <option value="Administrative Assistant III">Administrative Assistant III</option>
    <option value="Administrative Assistant III (Bookkeeper)">Administrative Assistant III (Bookkeeper)</option>
    <option value="Administrative Officer I">Administrative Officer I</option>
    <option value="Administrative Officer II">Administrative Officer II</option>
    <option value="Administrative Officer III">Administrative Officer III</option>
    <option value="Administrative Officer IV">Administrative Officer IV</option>
    <option value="Administrative Officer V">Administrative Officer V</option>
    <option value="Area Coordinator">Area Coordinator</option>
    <option value="Budget Assistant">Budget Assistant</option>
    <option value="Cash Clerk">Cash Clerk</option>
    <option value="Chief Administrative Officer">Chief Administrative Officer</option>
    <option value="Community Development Assistant II">Community Development Assistant II</option>
    <option value="Community Development Officer II">Community Development Officer II</option>
    <option value="Community Development Officer III">Community Development Officer III</option>
    <option value="Community Development Officer IV">Community Development Officer IV</option>
    <option value="Community Development Officer V">Community Development Officer V</option>
    <option value="Community Empowerment Facilitator">Community Empowerment Facilitator</option>
    <option value="Community Facilitator">Community Facilitator</option>
    <option value="Community Facilitator Aide">Community Facilitator Aide</option>
    <option value="Encoder">Encoder</option>
    <option value="Executive Assistant">Executive Assistant</option>
    <option value="Financial Analyst I">Financial Analyst I</option>
    <option value="Financial Analyst II">Financial Analyst II</option>
    <option value="Financial Analyst III">Financial Analyst III</option>
    <option value="Houseparent I">Houseparent I</option>
    <option value="Houseparent II">Houseparent II</option>
    <option value="Houseparent III">Houseparent III</option>
    <option value="Management and Audit Analyst II">Management and Audit Analyst II</option>
    <option value="Manpower Development Officer I">Manpower Development Officer I</option>
    <option value="Manpower Development Officer II">Manpower Development Officer II</option>
    <option value="Medical Officer IV">Medical Officer IV</option>
    <option value="Monitoring &amp; Evaluation Officer II">Monitoring &amp; Evaluation Officer II</option>
    <option value="Monitoring &amp; Evaluation Officer III">Monitoring &amp; Evaluation Officer III</option>
    <option value="Municipal Monitor">Municipal Monitor</option>
    <option value="Notifier">Notifier</option>
    <option value="Planning Officer I">Planning Officer I</option>
    <option value="Planning Officer II">Planning Officer II</option>
    <option value="Planning Officer III">Planning Officer III</option>
    <option value="Planning Officer IV">Planning Officer IV</option>
    <option value="Procurement Officer">Procurement Officer</option>
    <option value="Project Development Officer I">Project Development Officer I</option>
    <option value="Project Development Officer II">Project Development Officer II</option>
    <option value="Project Development Officer III">Project Development Officer III</option>
    <option value="Project Development Officer IV">Project Development Officer IV</option>
    <option value="Project Development Officer V">Project Development Officer V</option>
    <option value="Project Evaluation Officer III">Project Evaluation Officer III</option>
    <option value="Project Evaluation Officer IV">Project Evaluation Officer IV</option>
    <option value="Psychologist I">Psychologist I</option>
    <option value="Social Marketing Officer">Social Marketing Officer</option>
    <option value="Social Welfare Aide">Social Welfare Aide</option>
    <option value="Social Welfare Assistant">Social Welfare Assistant</option>
    <option value="Social Welfare Officer I">Social Welfare Officer I</option>
    <option value="Social Welfare Officer II">Social Welfare Officer II</option>
    <option value="Social Welfare Officer III">Social Welfare Officer III</option>
    <option value="Social Welfare Officer IV">Social Welfare Officer IV</option>
    <option value="Social Welfare Officer V">Social Welfare Officer V</option>
    <option value="Statistician Aide">Statistician Aide</option>
    <option value="Statistician II">Statistician II</option>
    <option value="Supervising Administrative Officer">Supervising Administrative Officer</option>
    <option value="Teacher (ECCD)">Teacher (ECCD)</option>
    <option value="Technical Facilitator">Technical Facilitator</option>
    <option value="Training Assistant">Training Assistant</option>
    <option value="Training Specialist I">Training Specialist I</option>
    <option value="Training Specialist II">Training Specialist II</option>
    <option value="Training Specialist III">Training Specialist III</option>
    <option value="Training Specialist IV">Training Specialist IV</option>
    <option value="Utility Worker">Utility Worker</option>
    <option value="Utility Worker II">Utility Worker II</option>
    <option value="Validator">Validator</option>
    <option value="Director II">Director II</option>
    <option value="Director III">Director III</option>
    <option value="Director IV">Director IV</option>
  </select>
</div>

    {{-- Assign Category --}}   
    <div>
        <label class="block text-sm font-medium text-gray-600">Assign Category</label>
        <select name="assigned_category" required 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" disabled selected>-- Select Category --</option>
            <option value="regular">Regular</option>
            <option value="priority">Priority</option>
            <option value="both">Both</option>
        </select>
    </div> 
                
            </div>

            {{-- Assign Step --}}
            <div class="grid grid-cols-2 gap-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Assign Step</label>
                    <select name="step_id" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>-- Select Step --</option>
                        @foreach($steps as $step)
                            <option value="{{ $step->id }}">{{ $step->step_number }} - {{ $step->step_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Assign Window</label>
                    <select name="window_id" required disabled
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>-- Select Window --</option>
                    </select>
                </div>
            </div>

            {{-- Assign Window --}}


            {{-- Password --}}
<div class="relative">
    <label class="block text-sm font-medium text-gray-600">Password</label>
    <input id="password" type="password" name="password" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Enter password">
    <!-- Eye button -->
    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500">
        <!-- Default: Eye icon -->
        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" />
            <path d="M10 7a3 3 0 100 6 3 3 0 000-6z" />
        </svg>
    </button>
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

        {{-- ✅ Fancy Users Table --}}
<div class="overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
    <table id="usersTable" class="min-w-full text-sm text-left border-collapse">
        <thead>
            <tr class="bg-[#150e60] text-white">
                @foreach($userColumns as $label)
                    <th class="px-6 py-3 font-semibold tracking-wide">{{ $label }}</th>
                @endforeach
                <th class="px-6 py-3 font-semibold tracking-wide">Assigned Step</th>
                <th class="px-6 py-3 font-semibold tracking-wide">Assigned Window</th>
                <th class="px-6 py-3 font-semibold tracking-wide text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($users as $u)
                <tr class="hover:bg-indigo-50 transition duration-200">
                    @foreach($userColumns as $field => $label)
                        <td class="px-6 py-3 text-gray-700">
                            @if($field === 'user_type')
                                {{ $u->getUserTypeName() }}
                            @else
                                {{ $u->$field ?? '—' }}
                            @endif
                        </td>
                    @endforeach


                    {{-- Assigned Step --}}
                    <td class="px-6 py-3 font-medium text-gray-700">
                        {{ $u->step->step_number ?? '—' }}
                    </td>

                    {{-- Assigned Window --}}
                    <td class="px-6 py-3 font-medium text-gray-700">
                        {{ $u->window->window_number ?? '—' }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-3 text-center space-x-2">
                        <a href="#"
                           class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                           <i class="fas fa-edit"></i> Edit
                        </a>
                        <button onclick="deleteUser({{ $u->id }})"
                                class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($userColumns) + 3 }}" class="px-6 py-6 text-center text-gray-500">
                        🚫 No users found.
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
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('addUserModal');
    const openBtn = document.getElementById('openAddUserModal');
    const closeBtn = document.getElementById('closeAddUserModal');
    const cancelBtn = document.getElementById('cancelAddUser');
    const form = document.getElementById('addUserForm');
    const tbody = document.querySelector('#usersTable tbody');

    // --- Modal Open / Close ---
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        setTimeout(() => modal.firstElementChild.classList.remove('scale-95'), 10);
    });

    const closeModal = () => {
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
        form.reset();
    };
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // --- Add User AJAX ---
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
            first_name: form.first_name.value,
            last_name: form.last_name.value,
            email: form.email.value,
            position: form.position.value,
            assigned_category: form.assigned_category.value,
            step_id: form.step_id.value,
            window_id: form.window_id.value,
            password: form.password.value
        };

        try {
            const res = await fetch("{{ route('admin.users.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const json = await res.json();

            if (!res.ok || !json.success) {
                // Validation errors or other server errors
                let msg = json.message || 'Unknown error';
                if (json.errors) {
                    msg += '\n' + Object.values(json.errors).flat().join('\n');
                }
                alert(msg);
                return;
            }

            // Success: Add row to table
            const u = json.user;
            const row = document.createElement('tr');
            row.id = `userRow-${u.id}`;
            row.className = 'hover:bg-indigo-50 transition duration-200';
            row.innerHTML = `
                <td class="px-6 py-3 text-gray-700">${u.first_name}</td>
                <td class="px-6 py-3 text-gray-700">${u.last_name}</td>
                <td class="px-6 py-3 text-gray-700">${u.email}</td>
                <td class="px-6 py-3 text-gray-700">${u.position}</td>
                <td class="px-6 py-3 text-gray-700">${u.user_type_name}</td>
                <td class="px-6 py-3 text-gray-700">${u.assigned_category}</td>
                <td class="px-6 py-3 text-gray-700">${u.step_number ?? '—'}</td>
                <td class="px-6 py-3 text-gray-700">${u.window_number ?? '—'}</td>
                <td class="px-6 py-3 text-center space-x-2">
                    <a href="#" class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button onclick="deleteUser(${u.id})" class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </td>
            `;
            tbody.appendChild(row);

            closeModal();

        } catch (err) {
            console.error('Add user failed:', err);
            alert('Add user failed. Check console for details.');
        }
    });

});

</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
const userTypeSelect = document.querySelector('select[name="user_type"]');
if (userTypeSelect) {
    const assignedCategory = document.querySelector('select[name="assigned_category"]');
    const stepSelect = document.querySelector('select[name="step_id"]');
    const windowSelect = document.querySelector('select[name="window_id"]');

    const toggleFields = () => {
        const selectedText = userTypeSelect.options[userTypeSelect.selectedIndex].text.toLowerCase();
        const isDisplay = selectedText === 'display';

        assignedCategory.disabled = isDisplay;
        stepSelect.disabled = isDisplay;
        windowSelect.disabled = isDisplay;

        [assignedCategory, stepSelect, windowSelect].forEach(field => {
            field.classList.toggle('bg-gray-100', isDisplay);
            field.classList.toggle('cursor-not-allowed', isDisplay);
        });
    };

    userTypeSelect.addEventListener('change', toggleFields);
    toggleFields();
}

});
</script>


<script>
    // Delete User Function
    function deleteUser(userId) {
        if (!confirm('Are you sure you want to delete this user?')) return;

        fetch(`${window.appBaseUrl}/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                // Remove the user row from the table
                const row = document.getElementById(`userRow-${userId}`);
                if (row) row.remove();
            } else {
                alert('Error deleting user: ' + (data.message ?? 'Unknown error'));
            }
        })
        .catch(err => {
            console.error('Delete user failed:', err);
            alert('An error occurred while deleting the user.');
        });
    }
</script>

<script>
function renderUsers(users) {
    const tbody = document.querySelector('#usersTable tbody');
    tbody.innerHTML = '';

    if (!users || users.length === 0) {
        tbody.innerHTML = `<tr>
            <td colspan="{{ count($userColumns) + 3 }}" class="px-6 py-6 text-center text-gray-500">
                🚫 No users found.
            </td>
        </tr>`;
        return;
    }

    users.forEach(u => {
        const row = document.createElement('tr');
        row.id = `userRow-${u.id}`;
        row.className = 'hover:bg-indigo-50 transition duration-200';

        row.innerHTML = `
            <td class="px-6 py-3 text-gray-700">${u.first_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.last_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.email}</td>
            <td class="px-6 py-3 text-gray-700">${u.position}</td>
            <td class="px-6 py-3 text-gray-700">${u.user_type_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.assigned_category}</td>

            <!-- IMPORTANT: Step then Window (match the table header) -->
            <td class="px-6 py-3 text-gray-700">${u.step_number ?? '—'}</td>
            <td class="px-6 py-3 text-gray-700">${u.window_number ?? '—'}</td>

            <td class="px-6 py-3 text-center space-x-2">
                <a href="#" class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button onclick="deleteUser(${u.id})" class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-sm transition duration-200">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}





// Poll the endpoint every 1 second
function fetchUsers() {
    fetch("{{ route('admin.users.json') }}")
        .then(res => res.json())
        .then(data => renderUsers(data))
        .catch(err => console.error(err));
}

// Initial fetch and interval
fetchUsers();
setInterval(fetchUsers, 1000);
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const stepSelect = document.querySelector('select[name="step_id"]');
    const windowSelect = document.querySelector('select[name="window_id"]');

    stepSelect.addEventListener('change', function () {
        const stepId = this.value;

        // Reset window dropdown
        windowSelect.innerHTML = '<option value="">-- Select Window --</option>';
        windowSelect.disabled = true;

        if (stepId) {
            // Use dynamic base URL
            const url = `${window.appBaseUrl}/windows/by-step/${stepId}`;

            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(w => {
                            const opt = document.createElement('option');
                            opt.value = w.id;
                            opt.textContent = w.window_number;
                            windowSelect.appendChild(opt);
                        });
                        windowSelect.disabled = false;
                    }
                })
                .catch(err => {
                    console.error('Failed to fetch windows:', err);
                });
        }
    });
});
</script>

{{-- Password eye toggle --}}
<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;

        // Optionally change the icon
        eyeIcon.innerHTML = type === 'password' 
            ? '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" /><path d="M10 7a3 3 0 100 6 3 3 0 000-6z" />' 
            : '<path d="M3.707 3.707a1 1 0 00-1.414 1.414l1.095 1.094C2.52 7.083 1.732 8.462 1 10c.73 2.89 4 7 9 7 1.605 0 3.123-.483 4.414-1.293l1.879 1.879a1 1 0 001.414-1.414l-14-14zM10 5a5 5 0 014.546 3.032l-1.479 1.478A3 3 0 0010 7a3 3 0 00-1.667.516L7.044 7.03A5 5 0 0110 5z"/>';
    });
</script>

@endsection
