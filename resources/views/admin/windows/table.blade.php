@extends('layouts.admin')
@section('title', 'Windows')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]">
    <div class="p-4 sm:ml-64">

        {{-- Header & Add Window --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Windows</h2>
            <button id="openAddWindowModal" 
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Add Window
            </button>
        </div>

        {{-- ✅ Add Window Modal --}}
        <div id="addWindowModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800">Add New Window</h3>
                    <button id="closeAddWindowModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                </div>

                <form id="addWindowForm" method="POST" action="{{ route('windows.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="step_id" class="block text-sm font-medium text-gray-700">Step</label>
                        <select id="step_id" name="step_id" class="mt-1 block w-full border rounded-md p-2" required>
                            @foreach($steps as $step)
                                <option value="{{ $step->id }}">{{ $step->step_number }} - {{ $step->step_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="window_number" class="block text-sm font-medium text-gray-700">Window Number</label>
                        <input type="number" id="window_number" name="window_number" min="1" max="10"
                            class="mt-1 block w-full border rounded-md p-2" required>
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                        Save Window
                    </button>
                </form>
            </div>
        </div>

        {{-- ✅ Fancy Windows Table --}}
        <div class="overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
            <table class="min-w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-[#150e60] text-white">
                        <th class="px-6 py-3 font-semibold tracking-wide">Window Number</th>
                        <th class="px-6 py-3 font-semibold tracking-wide">Step</th>
                        <th class="px-6 py-3 font-semibold tracking-wide text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($windows as $window)
                        <tr class="hover:bg-blue-50 transition duration-200" data-id="{{ $window->id }}">
                            {{-- Window Number --}}
                            <td class="px-6 py-3 font-medium text-gray-700">
                                {{ $window->window_number }}
                            </td>

                            {{-- Editable Step --}}
                            <td class="px-6 py-3">
                                <span class="editable-window-step cursor-pointer text-gray-800 hover:text-blue-600"
                                      data-id="{{ $window->id }}">
                                    {{ $window->step->step_number ?? '—' }} - {{ $window->step->step_name ?? '—' }}
                                </span>
                                <select class="hidden w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                        data-id="{{ $window->id }}">
                                    @foreach($steps as $step)
                                        <option value="{{ $step->id }}" 
                                            @if($window->step_id == $step->id) selected @endif>
                                            {{ $step->step_number }} - {{ $step->step_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            {{-- Delete Button --}}
                            <td class="px-6 py-3 text-center">
                                <button class="delete-window bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg shadow-sm transition duration-200"
                                    data-id="{{ $window->id }}">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-6">
                                🚫 No windows available.
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
    // ✅ Modal Toggle
    const openBtn = document.getElementById('openAddWindowModal');
    const closeBtn = document.getElementById('closeAddWindowModal');
    const modal = document.getElementById('addWindowModal');

    if (openBtn && modal) openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    if (closeBtn && modal) closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

    // ✅ Inline edit for step of a window
    const editableSpans = document.querySelectorAll('.editable-window-step');

    editableSpans.forEach(span => {
        span.addEventListener('click', () => {
            const select = span.nextElementSibling;
            if (!select) return;
            span.classList.add('hidden');
            select.classList.remove('hidden');
            select.focus();
        });
    });

    const selects = document.querySelectorAll('td select');
    selects.forEach(select => {
        select.addEventListener('blur', () => {
            const id = select.dataset.id;
            const newStepId = select.value;
            const span = select.previousElementSibling;

            if (!newStepId || newStepId == span.dataset.id) {
                select.classList.add('hidden');
                span.classList.remove('hidden');
                return;
            }

            fetch(`/windows/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ step_id: newStepId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const stepText = select.options[select.selectedIndex].text;
                    span.textContent = stepText;
                } else {
                    alert("Error updating window step.");
                }
                select.classList.add('hidden');
                span.classList.remove('hidden');
            })
            .catch(() => {
                alert("Failed to update window step.");
                select.classList.add('hidden');
                span.classList.remove('hidden');
            });
        });

        select.addEventListener('keydown', e => {
            if (e.key === 'Enter') select.blur();
            if (e.key === 'Escape') {
                select.classList.add('hidden');
                select.previousElementSibling.classList.remove('hidden');
            }
        });
    });

    // ✅ Delete window
    const deleteButtons = document.querySelectorAll('.delete-window');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            if (!confirm("Are you sure you want to delete this window?")) return;

            fetch(`/admin/windows/${id}`, {
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
                    alert("Error deleting window.");
                }
            })
            .catch(() => {
                alert("Failed to delete window.");
            });

        });
    });


    // ✅ Check if window_number already exists
    const windowInput = document.getElementById('window_number');
    const saveWindowBtn = document.querySelector('#addWindowForm button[type="submit"]');

    if (windowInput && saveWindowBtn) {
        windowInput.addEventListener('input', () => {
            const windowNumber = windowInput.value;
            const stepId = document.getElementById('step_id')?.value; // in case windows are unique per step
            if (!windowNumber) return;

            fetch(`/windows/check/${stepId}/${windowNumber}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        windowInput.setCustomValidity("Window number already exists for this step.");
                        windowInput.reportValidity();
                        saveWindowBtn.disabled = true;
                    } else {
                        windowInput.setCustomValidity("");
                        saveWindowBtn.disabled = false;
                    }
                })
                .catch(() => {
                    windowInput.setCustomValidity("Error checking window number.");
                    saveWindowBtn.disabled = true;
                });
        });
    }

});
</script>
@endsection
