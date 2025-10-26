@extends('layouts.admin')
@section('title', 'Windows')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-200">
    <div class="p-4 sm:ml-64">
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



                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelAddUser" class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cancel</button>
                        <button type="submit" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save Window</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
            {{-- Header & Add Window --}}
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Windows</h2>
                <button id="openAddWindowModal" 
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Add Window
                </button>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                    <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                        <tr>
                            <th class="text-left px-6 py-3 font-semibold tracking-wide rounded-tl-lg">Step Number</th>
                            <th class="text-left px-6 py-3 font-semibold tracking-wide">Window Number</th>
                            <th class="text-left px-6 py-3 font-semibold tracking-wide text-center rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                        @forelse($windows as $window)
                            <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200" data-id="{{ $window->id }}">
                                {{-- ✅ Step (read-only) --}}
                                <td class="text-left px-6 py-3">
                                    {{ $window->step->step_number ?? '—' }} - {{ $window->step->step_name ?? '—' }}
                                </td>

                                {{-- Window Number --}}
                                <td class="text-left  px-6 py-3 font-medium text-gray-700">
                                    {{ $window->window_number }}
                                </td>

                                {{-- Delete Button --}}
                                <td class="px-6 py-3 text-left ">
                                    @if($window->window_number == 1)
                                        <button class="bg-gray-400 text-white px-4 py-1.5 rounded-lg shadow-sm cursor-not-allowed" disabled>
                                            <i class="fas fa-ban"></i> Protected
                                        </button>
                                    @else
                                        <button class="delete-window text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            data-id="{{ $window->id }}">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    @endif
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
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ✅ Modal Toggle
    const openBtn = document.getElementById('openAddWindowModal');
    const closeBtn = document.getElementById('closeAddWindowModal');
    const cancelBtn = document.getElementById('cancelAddUser');
    const modal = document.getElementById('addWindowModal');

    if (openBtn && modal) openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    if (closeBtn && modal) closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    if (cancelBtn && modal) cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));

    // ✅ Delete window
    const deleteButtons = document.querySelectorAll('.delete-window');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            if (!confirm("Are you sure you want to delete this window?")) return;

    fetch(window.routes.deleteWindow.replace(':id', id), {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    alert("Error deleting window.");
                }
            })
            .catch(err => {
                console.error('Delete window failed:', err);
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
            const stepId = document.getElementById('step_id')?.value;
            if (!windowNumber || !stepId) return;

            fetch(
                    window.routes.checkWindow
                        .replace(':step', stepId)
                        .replace(':window', windowNumber)
                )
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
                .catch(err => {
                    console.error('Window uniqueness check failed:', err);
                    windowInput.setCustomValidity("Error checking window number.");
                    saveWindowBtn.disabled = true;
                });
        });
    }
});
</script>
@endsection
