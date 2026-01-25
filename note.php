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
                                <select name="position" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled selected>-- Select Position --</option>
                                    @foreach (\App\Libraries\Positions::all() as $position)
                                        <option value="{{ $position }}">{{ $position }}</option>
                                    @endforeach
                                </select>

                            </div>

                            {{-- put assign step here --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Assign Step</label>
                                <select name="step_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled selected>-- Select Step --</option>
                                    @foreach ($steps as $step)
                                        <option value="{{ $step->id }}">{{ $step->step_number }} -
                                            {{ $step->step_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Assign Step --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Assign Window</label>
                                <select name="window_id" required disabled
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled selected>-- Select Window --</option>
                                </select>
                            </div>
                            @php
                                use App\Libraries\Sections;
                            @endphp
                            @if (auth()->user()->section_id == Sections::CRISIS_INTERVENTION_SECTION())
                                <div id="assignCategoryWrapper">
                                    <label class="block text-sm font-medium text-gray-600">Assign Category</label>
                                    <select id="assignedCategorySelect" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled selected>-- Select Category --</option>
                                        <option value="regular">Regular</option>
                                        <option value="priority">Priority</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>
                            @endif

                            {{-- Hidden field, no name by default --}}
                            <input type="hidden" id="assignedCategoryHidden" name="assigned_category" value="both">
                        </div>

                        {{-- Password --}}
                        <div x-data="{ show: false }" class="relative">
                            <label for="password" class="block text-sm font-medium text-gray-600">Password</label>

                            <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter password">

                            <!-- Eye toggle button -->
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pt-4 pr-3 text-gray-500 focus:outline-none">

                                <!-- Closed eye -->
                                <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}"
                                    alt="Show Password" class="h-5 w-5">

                                <!-- Open eye -->
                                <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}"
                                    alt="Hide Password" class="h-5 w-5">
                            </button>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelAddUser"
                                class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Cancel</button>
                            <button type="submit"
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Users</h2>
                    <button id="openAddUserModal"
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Add User
                    </button>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table id="usersTable" class="min-w-full divide-y divide-gray-200 text-gray-700">
                        <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                            <tr>
                                @foreach ($userColumns as $label)
                                    <th class="px-6 py-3 font-semibold tracking-wide">{{ $label }}</th>
                                @endforeach
                                <th class="px-6 py-3 font-semibold tracking-wide">Assigned Step</th>
                                <th class="px-6 py-3 font-semibold tracking-wide">Assigned Window</th>
                                <th class="px-6 py-3 font-semibold tracking-wide text-center rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                            @forelse ($users as $u)
                                <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200">
                                    @foreach ($userColumns as $field => $label)
                                        <td class="px-6 py-3 text-gray-700">
                                            @if ($field === 'user_type')
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
                                        <button onclick="deleteUser({{ $u->id }})"
                                            class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                                            <i class="fas fa-trash-alt"></i> Delete
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
    <script>
        window.appBaseUrl = "{{ url('') }}";
        window.userColumnsCount = {{ count($userColumns) + 3 }};

        document.addEventListener("DOMContentLoaded", () => {
    // ===============================
    // Modal Logic
    // ===============================
    const modal = document.getElementById("addUserModal");
    const openBtn = document.getElementById("openAddUserModal");
    const closeBtn = document.getElementById("closeAddUserModal");
    const cancelBtn = document.getElementById("cancelAddUser");
    const form = document.getElementById("addUserForm");

    const openModal = () => {
        modal.classList.remove("hidden");
        setTimeout(
            () => modal.firstElementChild.classList.remove("scale-95"),
            10
        );
    };

    const closeModal = () => {
        modal.firstElementChild.classList.add("scale-95");
        setTimeout(() => modal.classList.add("hidden"), 200);
        form.reset();
    };

    openBtn?.addEventListener("click", openModal);
    closeBtn?.addEventListener("click", closeModal);
    cancelBtn?.addEventListener("click", closeModal);

    // ===============================
    // Add User Form
    // ===============================
    form?.addEventListener("submit", async (e) => {
        e.preventDefault();

        const nameRegex = /^[A-Za-z\s'-]+$/; // Allow letters, spaces, hyphens, apostrophes

        if (!nameRegex.test(form.first_name.value.trim())) {
            alert(
                "First name must contain only letters, spaces, apostrophes, or hyphens."
            );
            return;
        }

        if (!nameRegex.test(form.last_name.value.trim())) {
            alert(
                "Last name must contain only letters, spaces, apostrophes, or hyphens."
            );
            return;
        }
        const data = {
            first_name: form.first_name.value.trim(),
            last_name: form.last_name.value.trim(),
            email: form.email.value.trim(),
            position: form.position.value.trim(),
            assigned_category: form.assigned_category.value,
            step_id: form.step_id.value,
            window_id: form.window_id.value,
            password: form.password.value.trim(),
        };

        if (!data.password || data.password.trim() === "") {
            alert("Password cannot be empty!");
            return;
        }

        try {
            const res = await fetch(window.appBaseUrl + "/admin/users/store", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            });

            const json = await res.json();

            if (!res.ok || !json.success) {
                let msg = json.message || "Unknown error";
                if (json.errors) {
                    msg += "\n" + Object.values(json.errors).flat().join("\n");
                }
                alert(msg);
                return;
            }

            fetchUsers();
            closeModal();
        } catch (err) {
            console.error("Add user failed:", err);
            alert("Add user failed. Check console for details.");
        }
    });

    // ===============================
    // User Type Toggle Fields
    // ===============================
    const userTypeSelect = document.querySelector('select[name="user_type"]');
    if (userTypeSelect) {
        const assignedCategory = document.querySelector(
            'select[name="assigned_category"]'
        );
        const stepSelect = document.querySelector('select[name="step_id"]');
        const windowSelect = document.querySelector('select[name="window_id"]');

        const toggleFields = () => {
            const selectedText =
                userTypeSelect.options[
                    userTypeSelect.selectedIndex
                ].text.toLowerCase();
            const isDisplay = selectedText === "display";

            assignedCategory.disabled = isDisplay;
            stepSelect.disabled = isDisplay;
            windowSelect.disabled = isDisplay;

            [assignedCategory, stepSelect, windowSelect].forEach((field) => {
                field.classList.toggle("bg-gray-100", isDisplay);
                field.classList.toggle("cursor-not-allowed", isDisplay);
            });
        };

        userTypeSelect.addEventListener("change", toggleFields);
        toggleFields();
    }

    // ===============================
    // Step → Window Dropdown
    // ===============================
    const stepSelect = document.querySelector('select[name="step_id"]');
    const windowSelect = document.querySelector('select[name="window_id"]');

    stepSelect?.addEventListener("change", function () {
        const stepId = this.value;

        windowSelect.innerHTML =
            '<option value="">-- Select Window --</option>';
        windowSelect.disabled = true;

        if (stepId) {
            fetch(`${window.appBaseUrl}/windows/by-step/${stepId}`)
                .then((res) => res.json())
                .then((data) => {
                    if (data.length > 0) {
                        data.forEach((w) => {
                            const opt = document.createElement("option");
                            opt.value = w.id;
                            opt.textContent = w.window_number;
                            windowSelect.appendChild(opt);
                        });
                        windowSelect.disabled = false;
                    }
                })
                .catch((err) => console.error("Failed to fetch windows:", err));
        }
    });

    // ===============================
    // Step → Assign Category Logic
    // ===============================
    const stepSelect2 = document.querySelector('select[name="step_id"]');
    const categoryWrapper = document.getElementById("assignCategoryWrapper");
    const categorySelect = document.getElementById("assignedCategorySelect");
    const categoryHidden = document.getElementById("assignedCategoryHidden");
    const bothOption = categorySelect?.querySelector('option[value="both"]');

    function updateCategoryVisibility() {
        if (
            !stepSelect2 ||
            !categoryWrapper ||
            !categorySelect ||
            !categoryHidden
        )
            return;

        const selectedText =
            stepSelect2.options[stepSelect2.selectedIndex]?.text || "";
        const stepNumber = parseInt(selectedText.split("-")[0]?.trim()) || null;

        const isStep3or4 = stepNumber === 3 || stepNumber === 4;

        // Hide dropdown entirely for step 3 or 4 → auto-assign "both"
        if (isStep3or4) {
            categoryWrapper.style.display = "none";
            categorySelect.removeAttribute("name");
            categorySelect.required = false;
            categorySelect.disabled = true;

            categoryHidden.value = "both";
            categoryHidden.setAttribute("name", "assigned_category");
        } else {
            // Show dropdown normally
            categoryWrapper.style.display = "";
            categorySelect.setAttribute("name", "assigned_category");
            categorySelect.required = true;
            categorySelect.disabled = false;

            categoryHidden.removeAttribute("name");
        }

        // ✅ Extra Rule: Hide "Both" option when step_number = 1 or 2
        if (stepNumber === 1 || stepNumber === 2) {
            if (bothOption) {
                bothOption.style.display = "none";
                if (categorySelect.value === "both") {
                    categorySelect.value = ""; // reset if user had picked "Both"
                }
            }
        } else {
            if (bothOption) {
                bothOption.style.display = "block";
            }
        }
    }

    // Run only if wrapper exists
    if (stepSelect2 && categoryWrapper) {
        updateCategoryVisibility();
        stepSelect2.addEventListener("change", updateCategoryVisibility);
    }
});

// ===============================
// Delete User
// ===============================
function deleteUser(userId) {
    if (!confirm("Are you sure you want to delete this user?")) return;
    fetch(`${window.appBaseUrl}/admin/users/${userId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            Accept: "application/json",
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                document.getElementById(`userRow-${userId}`)?.remove();
            } else {
                alert(
                    "Error deleting user: " + (data.message ?? "Unknown error")
                );
            }
        })
        .catch((err) => {
            console.error("Delete user failed:", err);
            alert("An error occurred while deleting the user.");
        });
}

// ===============================
// Render + Fetch Users
// ===============================
function renderUsers(users) {
    const tbody = document.querySelector("#usersTable tbody");
    tbody.innerHTML = "";

    if (!users || users.length === 0) {
        tbody.innerHTML = `<tr>
            <td colspan="${window.userColumnsCount}" class="px-6 py-3 text-center text-gray-500">
                🚫 No users found.
            </td>
        </tr>`;
        return;
    }

    users.forEach((u) => {
        const row = document.createElement("tr");
        row.id = `userRow-${u.id}`;
        row.className =
            "odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200";

        row.innerHTML = `
            <td class="px-6 py-3 text-gray-700">${u.first_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.last_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.email}</td>
            <td class="px-6 py-3 text-gray-700">${u.position}</td>
            <td class="px-6 py-3 text-gray-700">${u.user_type_name}</td>
            <td class="px-6 py-3 text-gray-700">${u.assigned_category}</td>
            <td class="px-6 py-3 text-gray-700">${u.step_number ?? "—"}</td>
            <td class="px-6 py-3 text-gray-700">${u.window_number ?? "—"}</td>
            <td class="px-6 py-3 text-center space-x-2"></td>
        `;

        // ✅ Create button in JS
        const actionCell = row.querySelector("td:last-child");
        const delBtn = document.createElement("button");
        delBtn.className =
            "text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 \
                            hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 \
                            dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 \
                            font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2";
        delBtn.innerHTML = `<i class="fas fa-trash-alt"></i> Delete`;

        // ✅ Attach event listener
        delBtn.addEventListener("click", () => deleteUser(u.id));

        actionCell.appendChild(delBtn);
        tbody.appendChild(row);
    });
}

function fetchUsers() {
    fetch(window.appBaseUrl + "/admin/users/json")
        .then((res) => res.json())
        .then((data) => renderUsers(data))
        .catch((err) => console.error(err));
}

// Initial fetch and interval
fetchUsers();
setInterval(fetchUsers, 1000);

// ===============================
// Password Eye Toggle
// ===============================
const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");
const eyeIcon = document.getElementById("eyeIcon");

togglePassword?.addEventListener("click", () => {
    const type = passwordInput.type === "password" ? "text" : "password";
    passwordInput.type = type;

    eyeIcon.innerHTML =
        type === "password"
            ? '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" /><path d="M10 7a3 3 0 100 6 3 3 0 000-6z" />'
            : '<path d="M3.707 3.707a1 1 0 00-1.414 1.414l1.095 1.094C2.52 7.083 1.732 8.462 1 10c.73 2.89 4 7 9 7 1.605 0 3.123-.483 4.414-1.293l1.879 1.879a1 1 0 001.414-1.414l-14-14zM10 5a5 5 0 014.546 3.032l-1.479 1.478A3 3 0 0010 7a3 3 0 00-1.667.516L7.044 7.03A5 5 0 0110 5z"/>';
});



    </script>
    @vite('resources/js/adminUsers.js')
@endsection
