@extends('layouts.admin')
@section('title', 'Steps')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]">
    <div class="p-4 sm:ml-64">

        {{-- Header & Add Step --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Steps</h2>
            <button id="openAddUserModal" 
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Add Step
            </button>
        </div>

        {{-- ✅ Add Step Modal --}}
    <div id="addUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Add New Step</h3>
                <button id="closeAddUserModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
            </div>

            <form id="addStepForm" method="POST" action="{{ route('steps.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="step_number" class="block text-sm font-medium text-gray-700">Step Number</label>
                    <input type="number" id="step_number" name="step_number" 
                        min="0"
                        class="mt-1 block w-full border rounded-md p-2"
                        required>
                </div>

                <div class="mb-4">
                    <label for="step_name" class="block text-sm font-medium text-gray-700">Step Name</label>
                    <select id="step_name" name="step_name" 
                            class="mt-1 block w-full border rounded-md p-2">
                        <option value="None">None</option> 
                        <option value="Encode">Encode</option>
                        <option value="Initial Review">Initial Review</option>
                        <option value="Verification">Verification</option>
                        <option value="Assesment">Assesment</option>
                        <option value="Approval">Approval</option>
                        <option value="Release">Release</option>
                        {{-- add more if needed --}}
                    </select>
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Save Step
                </button>
            </form>
        </div>
    </div>


{{-- ✅ Fancy Steps Table --}}
<div class="overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
    <table class="min-w-full text-sm text-left border-collapse">
        <thead>
            <tr class="bg-[#150e60] text-white">
                <th class="px-6 py-3 font-semibold tracking-wide">Step Number</th>
                <th class="px-6 py-3 font-semibold tracking-wide">Step Name</th>
                <th class="px-6 py-3 font-semibold tracking-wide text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($steps as $step)
                <tr class="hover:bg-blue-50 transition duration-200" data-id="{{ $step->id }}">
                    {{-- Step Number --}}
                    <td class="px-6 py-3 font-medium text-gray-700">
                        {{ $step->step_number }}
                    </td>

                    {{-- Editable Step Name --}}
                    <td class="px-6 py-3">
                        <span class="editable-step-name cursor-pointer text-gray-800 hover:text-blue-600"
                              data-id="{{ $step->id }}">
                            {{ $step->step_name }}
                        </span>
                        <input type="text"
                               class="hidden w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                               value="{{ $step->step_name }}"
                               data-id="{{ $step->id }}">
                    </td>

                    {{-- Delete Button --}}
                    <td class="px-6 py-3 text-center">
                        <button class="delete-step bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg shadow-sm transition duration-200"
                            data-id="{{ $step->id }}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
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


@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sectionId = "{{ Auth::user()->section_id }}";

    // ✅ Modal Toggle
    const openBtn = document.getElementById('openAddUserModal');
    const closeBtn = document.getElementById('closeAddUserModal');
    const closeBtnFooter = document.getElementById('closeAddUserModalBtn');
    const modal = document.getElementById('addUserModal');

    if (openBtn && modal) {
        openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    }
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    }
    if (closeBtnFooter && modal) {
        closeBtnFooter.addEventListener('click', () => modal.classList.add('hidden'));
    }

    // ✅ Step number uniqueness check
    const stepInput = document.getElementById('step_number');
    const saveBtn = document.querySelector('form button[type="submit"]');

    if (stepInput && saveBtn) {
        stepInput.addEventListener('input', () => {
            const stepNumber = stepInput.value;
            if (!stepNumber) return;

            fetch(`/steps/check/${sectionId}/${stepNumber}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        stepInput.setCustomValidity("Step number already exists in your section.");
                        stepInput.reportValidity();
                        saveBtn.disabled = true;
                    } else {
                        stepInput.setCustomValidity("");
                        saveBtn.disabled = false;
                    }
                });
        });
    }

    // ✅ Inline edit for step names
    const editableSpans = document.querySelectorAll('.editable-step-name');
    const textInputs = document.querySelectorAll('td input[type="text"]');

    editableSpans.forEach(span => {
        span.addEventListener('click', () => {
            const input = span.nextElementSibling;
            if (!input) return;

            span.classList.add('hidden');
            input.classList.remove('hidden');
            input.focus();
        });
    });

    textInputs.forEach(input => {
        input.addEventListener('blur', () => {
            const id = input.dataset.id;
            const newValue = input.value.trim();
            const span = input.previousElementSibling;

            if (!newValue || newValue === span.textContent.trim()) {
                input.classList.add('hidden');
                span.classList.remove('hidden');
                return;
            }

            fetch(`/steps/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ step_name: newValue })
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
    const deleteButtons = document.querySelectorAll('.delete-step');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            if (!confirm("Are you sure you want to delete this step?")) return;

            fetch(`/steps/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) row.remove();
                } else {
                    alert("Error deleting step.");
                }
            })
            .catch(() => {
                alert("Failed to delete step.");
            });
        });
    });
});
</script>
@endsection
