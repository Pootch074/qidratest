@extends('layouts.admin')
@section('title', 'Steps')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            {{-- ✅ Add Step Modal --}}
            <div id="addUserModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
                <div
                    class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800">Add New Step</h3>
                        <button id="closeAddUserModal"
                            class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                    </div>

                    <form id="addStepForm" method="POST" action="{{ route('steps.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="step_name" class="block text-sm font-medium text-gray-700">Step Name</label>
                            <select id="stepName" name="step_name" required
                                class="mt-1 block w-full border rounded-md p-2">
                                @foreach (\App\Libraries\StepNames::all() as $step_name)
                                    <option value="{{ $step_name }}">{{ $step_name }}</option>
                                @endforeach
                            </select>
                            <p id="stepNameError" class="text-red-600 text-sm mt-1 hidden"></p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelAddUser"
                                class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Cancel
                            </button>
                            <button type="submit"
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Save Step
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Header & Add Step --}}
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col" x-data="stepModal()">
                <div x-show="showSteps" x-cloak>
                    <button type="button" @click="showModal = true"
                        class="w-1/2 h-24 flex items-center justify-center rounded-lg bg-[#2e3192] text-white font-bold shadow-md transition hover:bg-[#5057c9]">
                        Add Step
                    </button>
                </div>

                <div x-show="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/30 p-4" x-cloak>
                    <div class="relative w-full max-w-3xl max-h-full">
                        <div
                            class="relative bg-gray-200 rounded-2xl shadow-2xl border-2 border-blue-900 transform transition-all duration-200 scale-95 opacity-100">
                            <button type="button" @click="reset()"
                                class="absolute top-3 right-3 text-gray-700 hover:text-gray-400 bg-transparent rounded-full w-8 h-8 flex items-center justify-center transition">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M1 1l6 6m0 0 6-6M7 7l6 6M7 7l-6-6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>

                            <!-- Modal Header -->
                            <div class="px-6 pt-6 pb-4 text-center">
                                <h3 class="text-3xl font-bold text-gray-700 mb-2">Add New Step</h3>
                            </div>

                            <!-- Modal Form -->
                            <form class="px-6 pb-6 space-y-6" method="POST" id="clientLog" x-data="{ clientType: '', clientName: '', clientPhone: '' }">
                                @csrf
                                <div class="mb-4">
                                    <label for="step_name" class="block text-sm font-medium text-gray-700">Step Name</label>
                                    <select id="stepName" name="step_name" required
                                        class="mt-1 block w-full border rounded-md p-2">
                                        @foreach (\App\Libraries\StepNames::all() as $step_name)
                                            <option value="{{ $step_name }}">{{ $step_name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="stepNameError" class="text-red-600 text-sm mt-1 hidden"></p>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button type="button" id="cancelAddUser"
                                        class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                        Save Step
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Steps</h2>
                    <button id="openAddUserModal"
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Add Step
                    </button>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                        <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                            <tr>
                                <th class="text-left px-6 py-3 font-semibold tracking-wide rounded-tl-lg">Step Number</th>
                                <th class="text-left px-6 py-3 font-semibold tracking-wide">Step Name</th>
                                <th class="text-left px-6 py-3 font-semibold tracking-wide rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                            @forelse($steps as $step)
                                <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200"
                                    data-id="{{ $step->id }}">
                                    {{-- Step Number --}}
                                    <td class="text-left px-6 py-3 font-medium text-gray-700">
                                        {{ $step->step_number }}
                                    </td>

                                    {{-- Editable Step Name --}}
                                    <td class="text-left px-6 py-3">
                                        <span class="editable-step-name cursor-pointer text-gray-800 hover:text-blue-600"
                                            data-id="{{ $step->id }}">
                                            {{ $step->step_name }}
                                        </span>
                                        <input type="text"
                                            class="hidden w-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                            value="{{ $step->step_name }}" data-id="{{ $step->id }}">
                                    </td>

                                    {{-- Delete Button --}}
                                    <td class="px-6 py-3 text-left">
                                        @if ($step->step_number === 1)
                                            <button
                                                class="bg-gray-400 text-white px-4 py-1.5 rounded-lg shadow-sm cursor-not-allowed"
                                                disabled>
                                                <i class="fas fa-ban"></i> Protected
                                            </button>
                                        @else
                                            <button
                                                class="delete-step text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2"
                                                data-id="{{ $step->id }}">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500 py-6">
                                        🚫 No steps available for your section.
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
        function stepModal(userSectionId) {
            return {
                showSteps: true,
                showModal: false,
                clientName: '',
                clientPhone: '',
                userSectionId: userSectionId, // keep track of logged-in user’s section_id

                async generateQueue(sectionId, type) {
                    try {
                        const url = window.routes.pacdGenerate.replace('__SECTION__', sectionId);
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                client_type: type,
                                manual_client_name: this.clientName,
                                manual_client_phone: this.clientPhone
                            })
                        });

                        const data = await res.json();
                        if (!data || !data.success) {
                            console.error('Server returned error:', data);
                            alert('Error generating queue.');
                            return;
                        }

                        // Decide how many times to print
                        const copies = (this.userSectionId == 15) ? 2 : 1;

                        for (let i = 0; i < copies; i++) {
                            const iframe = document.createElement('iframe');
                            Object.assign(iframe.style, {
                                position: 'fixed',
                                right: '9999px',
                                width: '0',
                                height: '0',
                                border: '0'
                            });
                            document.body.appendChild(iframe);

                            const doc = iframe.contentDocument || iframe.contentWindow.document;
                            doc.open();
                            doc.write(ticketHtml);
                            doc.close();

                            iframe.onload = () => {
                                const printWindow = iframe.contentWindow;

                                printWindow.onafterprint = () => {
                                    try {
                                        document.body.removeChild(iframe);
                                    } catch {}
                                };

                                printWindow.focus();
                                setTimeout(() => {
                                    printWindow.print();
                                }, 150);
                            };
                        }

                        this.reset();

                    } catch (err) {
                        console.error('Request/print error:', err);
                        alert('Printing failed — check console for details.');
                    }
                },

                escapeHtml(str) {
                    return String(str || '').replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                },

                reset() {
                    this.showModal = false;
                    this.clientName = '';
                    this.clientPhone = '';
                }
            };
        }
    </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sectionId = "{{ Auth::user()->section_id }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // ✅ Modal Toggle
            const openBtn = document.getElementById('openAddUserModal');
            const closeBtn = document.getElementById('closeAddUserModal');
            const cancelBtn = document.getElementById('cancelAddUser');
            const modal = document.getElementById('addUserModal');

            if (openBtn && modal) openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            if (closeBtn && modal) closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            if (cancelBtn && modal) cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));

            // ✅ Step Name Duplicate Check
            const stepNameSelect = document.getElementById('stepName');
            const saveButton = document.querySelector('#addStepForm button[type="submit"]');
            const stepNameError = document.getElementById('stepNameError');

            stepNameSelect.addEventListener('change', () => {
                const stepName = stepNameSelect.value;

                if (!stepName || stepName === "None") {
                    stepNameSelect.setCustomValidity("");
                    stepNameError.textContent = "";
                    stepNameError.classList.add('hidden');
                    saveButton.disabled = false;
                    return;
                }

                fetch(`${window.appBaseUrl}/steps/check-name/${sectionId}/${encodeURIComponent(stepName)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            stepNameSelect.setCustomValidity("This step name already exists.");
                            stepNameError.textContent =
                                "This step name already exists in your section.";
                            stepNameError.classList.remove('hidden');
                            saveButton.disabled = true;
                        } else {
                            stepNameSelect.setCustomValidity("");
                            stepNameError.textContent = "";
                            stepNameError.classList.add('hidden');
                            saveButton.disabled = false;
                        }
                        stepNameSelect.reportValidity();
                    })
                    .catch(err => console.error("Step name check failed:", err));


            });

            // ✅ Inline edit for step names
            document.querySelectorAll('.editable-step-name').forEach(span => {
                span.addEventListener('click', () => {
                    const input = span.nextElementSibling;
                    if (!input) return;
                    span.classList.add('hidden');
                    input.classList.remove('hidden');
                    input.focus();
                });
            });

            document.querySelectorAll('td input[type="text"]').forEach(input => {
                input.addEventListener('blur', () => {
                    const id = input.dataset.id;
                    const newValue = input.value.trim();
                    const span = input.previousElementSibling;

                    // Prevent duplicate names except for "None"
                    if (newValue !== "None") {
                        fetch(
                                `${window.appBaseUrl}/steps/check-name/${sectionId}/${encodeURIComponent(newValue)}`
                            )
                            .then(res => res.json())
                            .then(data => {
                                // If duplicate AND not the same row
                                if (data.exists && newValue !== span.textContent.trim()) {
                                    alert("This step name already exists in your section.");
                                    input.value = span.textContent.trim();
                                    input.classList.add('hidden');
                                    span.classList.remove('hidden');
                                    return;
                                }

                                // Proceed to update if no duplicate
                                updateStepName();
                            });
                    } else {
                        updateStepName(); // Auto-allow "None"
                    }

                    function updateStepName() {
                        fetch(`${window.appBaseUrl}/steps/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    step_name: newValue,
                                    _method: 'PUT'
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    span.textContent = newValue;
                                } else {
                                    alert("Error updating step name.");
                                }
                                input.classList.add('hidden');
                                span.classList.remove('hidden');
                            })
                            .catch(() => {
                                alert("Failed to update step name.");
                                input.classList.add('hidden');
                                span.classList.remove('hidden');
                            });
                    }



                });

                input.addEventListener('keydown', e => {
                    if (e.key === 'Enter') input.blur();
                    if (e.key === 'Escape') {
                        input.value = input.previousElementSibling.textContent.trim();
                        input.classList.add('hidden');
                        input.previousElementSibling.classList.remove('hidden');
                    }
                });
            });

            // ✅ Delete step
            document.querySelectorAll('.delete-step').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    if (!confirm("Are you sure you want to delete this step?")) return;

                    fetch(`${window.appBaseUrl}/steps/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const row = document.querySelector(`tr[data-id="${id}"]`);
                                if (row) row.remove();
                            } else {
                                alert("Error deleting step.");
                            }
                        })
                        .catch(err => {
                            console.error('Delete step failed:', err);
                            alert("Failed to delete step.");
                        });
                });
            });

        });
    </script> --}}
@endsection
