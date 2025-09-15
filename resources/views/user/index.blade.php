@extends('layouts.main')

@section('title', 'Encode')

@section('content')
<div class="w-full h-[84vh] p-4 bg-gray-200">
    <div class="grid grid-cols-3 gap-2 h-full">

        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">UPCOMING</div>

            <div class="grid grid-cols-2 gap-4 p-2 bg-white rounded-b-lg border-2 border-[#2e3192] flex-1">
                {{-- Regular --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">REGULAR</div>
                    <div id="upcomingRegu" class="flex-1 bg-white p-2 overflow-y-auto max-h-[70vh]">

                    </div>
                </div>

                {{-- Priority --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">PRIORITY</div>
                    <div id="upcomingPrio" class="flex-1 bg-white p-2 overflow-y-auto max-h-[70vh]">

                    </div>
                </div>
            </div>
        </div>

                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">PENDING</div>

                    <div class="grid grid-cols-2 gap-4 p-2 bg-white rounded-b-lg border-2 border-[#2e3192] flex-1">
                        {{-- Regular --}}
                        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                            <div class="bg-[#2e3192] text-white text-center font-bold py-2">REGULAR</div>
                            <div id="pendingRegu" class="flex-1 bg-white p-2 overflow-y-auto max-h-[70vh]">
                                {{-- Fetched Regular Queues --}}
                            </div>
                        </div>

                        {{-- Priority --}}
                        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                            <div class="bg-[#2e3192] text-white text-center font-bold py-2">PRIORITY</div>
                            <div id="pendingPrio" class="flex-1 bg-white p-2 overflow-y-auto max-h-[70vh]">
                                {{-- Fetched Priority Queues --}}
                            </div>
                        </div>

                    </div>
                </div>


        {{-- SERVING --}}
        <div class="w-full flex flex-col h-full bg-white rounded-b-lg border-2 border-[#2e3192] shadow overflow-hidden">
            {{-- Step & Window --}}
            <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">
                @if($stepNumber || $windowNumber)
                    STEP {{ $stepNumber ?? '-' }} &nbsp; WINDOW {{ $windowNumber ?? '-' }}
                @endif
            </div>

            {{-- Office Info --}}
            <div class="bg-white p-4 text-center font-bold text-lg space-y-1">
                <p>{{ strtoupper($fieldOffice ?? '-') }}</p>
                <p>{{ strtoupper($divisionName ?? '-') }}</p>
                <p>{{ strtoupper($sectionName ?? '-') }}</p>
            </div>

            {{-- Now Serving --}}
            <div class="flex flex-col items-center bg-white flex-1 overflow-hidden">
                <div class="w-full p-6 text-center text-4xl font-bold">Now Serving</div>
                    <div id="servingQueue" class="w-full p-6 text-center text-7xl font-bold overflow-y-auto max-h-[70vh]">
                    </div>

            </div>

            {{-- Actions --}}
            <div class="flex flex-col bg-white p-4 space-y-3 flex-1">
                <div class="flex space-x-2 w-full mb-5">
                    <button id="nextRegularBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 
                        shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Next Regular</button>
                    <button id="nextPriorityBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 
                        shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Next Priority</button>
                </div>
                <div class="flex space-x-2 w-full">
                    <button id="skipBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br 
                        focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 
                        shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Skip</button>
                    <button id="recallBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 hover:bg-gradient-to-br 
                        focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 
                        shadow-lg shadow-orange-500/50 dark:shadow-lg dark:shadow-orange-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Recall</button>
                    <button id="proceedBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br 
                        focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 
                        shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const servingQueueEl = document.getElementById('servingQueue');
    const nextRegularBtn = document.getElementById('nextRegularBtn');
    const nextPriorityBtn = document.getElementById('nextPriorityBtn');
    const skipBtn = document.getElementById('skipBtn');
    const recallBtn = document.getElementById('recallBtn');
    const proceedBtn = document.getElementById('proceedBtn');

    // Utility to toggle button enabled/disabled + styles
    function setBtnState(btn, enabled) {
        if (!btn) return;
        btn.disabled = !enabled;
        if (enabled) {
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // 🔄 Toggle button states depending on serving queue
    function updateButtonStates() {
        const content = servingQueueEl ? servingQueueEl.innerText.trim() : '';
        const isEmpty = content.includes("🚫Empty");

        if (isEmpty) {
            // 🚫Empty → allow Next buttons, disable others
            setBtnState(nextRegularBtn, true);
            setBtnState(nextPriorityBtn, true);
            setBtnState(skipBtn, false);
            setBtnState(recallBtn, false);
            setBtnState(proceedBtn, false);
        } else {
            // Serving queue present → disable Next, enable action buttons
            setBtnState(nextRegularBtn, false);
            setBtnState(nextPriorityBtn, false);
            setBtnState(skipBtn, true);
            setBtnState(recallBtn, true);
            setBtnState(proceedBtn, true);
        }
    }

    const renderQueue = (list, containerId) => {
        const container = document.querySelector(containerId);
        if (!container) return;

        if (!list || list.length === 0) {
            container.innerHTML = `<div class="text-gray-400 text-center py-4 text-sm">🚫Empty</div>`;
            return;
        }

        container.innerHTML = list.map(queue => `
            <div class="${queue.style_class} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold">
                ${queue.formatted_number}
            </div>
        `).join('');
    };

    const fetchQueues = () => {
        fetch("{{ route('queues.data') }}")
            .then(res => res.json())
            .then(data => {
                renderQueue(data.upcomingRegu, '#upcomingRegu');
                renderQueue(data.upcomingPrio, '#upcomingPrio');
                renderQueue(data.pendingRegu, '#pendingRegu');
                renderQueue(data.pendingPrio, '#pendingPrio');
                renderQueue(data.servingQueue, '#servingQueue');

                updateButtonStates(); // 🔑 always update after refresh
            })
            .catch(err => console.error(err));
    };

    // Initial load + auto refresh
    fetchQueues();
    setInterval(fetchQueues, 2000);

    // Button handlers
    const bindAction = (btnId, routeName) => {
        const btn = document.getElementById(btnId);
        if (!btn) return;
        btn.addEventListener('click', () => {
            fetch(routeName, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                fetchQueues(); // refresh UI after action
            })
            .catch(err => console.error(err));
        });
    };

    bindAction('nextRegularBtn', "{{ route('users.nextRegular') }}");
    bindAction('nextPriorityBtn', "{{ route('users.nextPriority') }}");
    bindAction('skipBtn', "{{ route('users.skipQueue') }}");
    bindAction('proceedBtn', "{{ route('users.proceedQueue') }}");

    // 🟢 Also check once on DOM ready
    updateButtonStates();
});
</script>


<style>
/* Shared queue button base style */
.queue-btn {
    @apply text-white font-medium rounded-lg text-sm py-2.5 text-center transition shadow;
}
</style>
@endsection
