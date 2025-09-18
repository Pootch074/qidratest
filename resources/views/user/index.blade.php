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




<!-- Confirmation Modal (Light + Blur) -->
<div id="popup-modal" tabindex="-1" class="hidden fixed inset-0 z-50 flex mt-10 items-center justify-center backdrop-blur-m p-4">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-gray-200 rounded-lg shadow-lg transform transition-all duration-150 scale-95 opacity-0 border-2 border-[#2e3192]">
            <!-- Close button -->
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" id="modalCloseBtn">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>

            <div class="p-4 md:p-5 text-center">
                {{-- <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg> --}}
                <img class="mx-auto mb-4 text-gray-400 w-13 h-13" src="{{ Vite::asset('resources/images/icons/alert-circle.png') }}" alt="">

                <h3 id="modalMessage" class="mb-5 text-lg font-normal text-gray-700">Are you sure you want to perform this action?</h3>


                <button id="modalConfirmBtn" type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    Yes, confirm
                </button>


                <button id="modalCancelBtn" type="button" class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    No, cancel
                </button>
            </div>
        </div>
    </div>
</div>




@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Elements
    const servingQueueEl = document.getElementById('servingQueue');

    // Flowbite Modal Elements
    const modal = document.getElementById('popup-modal');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const modalCancelBtn = document.getElementById('modalCancelBtn');
    const modalCloseBtn = document.getElementById('modalCloseBtn');

    let currentAction = null;

    /*** === Modal Functions === ***/
    const showModal = (message, actionCallback) => {
        modalMessage.textContent = message;
        currentAction = actionCallback;
        modal.classList.remove('hidden');
        // Animate in
        const modalContent = modal.querySelector('div.relative.bg-gray-200');
        modalContent.classList.remove('opacity-0', 'scale-95');
        modalContent.classList.add('opacity-100', 'scale-100');
    };

    const hideModal = () => {
        const modalContent = modal.querySelector('div.relative.bg-gray-200');
        modalContent.classList.remove('opacity-100', 'scale-100');
        modalContent.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentAction = null;
        }, 150); // match transition
    };

    modalCancelBtn.addEventListener('click', hideModal);
    modalCloseBtn.addEventListener('click', hideModal);
    modalConfirmBtn.addEventListener('click', () => {
        if (currentAction) currentAction();
        hideModal();
    });

    /*** === Button State Management === */
    function setBtnState(btn, enabled) {
        if (!btn) return;
        btn.disabled = !enabled;
        btn.classList.toggle('opacity-50', !enabled);
        btn.classList.toggle('cursor-not-allowed', !enabled);
    }

    function updateButtonStates() {
        const content = servingQueueEl?.innerText.trim() || '';
        const isEmpty = content.includes("🚫Empty");

        setBtnState(document.getElementById('nextRegularBtn'), isEmpty);
        setBtnState(document.getElementById('nextPriorityBtn'), isEmpty);
        setBtnState(document.getElementById('skipBtn'), !isEmpty);
        setBtnState(document.getElementById('recallBtn'), !isEmpty);
        setBtnState(document.getElementById('proceedBtn'), !isEmpty);
    }

    /*** === Queue Rendering & Fetching === */
    const renderQueue = (list, containerId) => {
        const container = document.querySelector(containerId);
        if (!container) return;

        if (!list?.length) {
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
                updateButtonStates();
            })
            .catch(err => console.error(err));
    };

    fetchQueues();
    setInterval(fetchQueues, 1000);

    /*** === Bind Buttons With Confirmation Modal === */
    const bindActionWithConfirm = (btnId, routeName, message) => {
        const btn = document.getElementById(btnId);
        if (!btn) return;

        btn.addEventListener('click', () => {
            showModal(message, () => {
                if (!routeName) return;
                fetch(routeName, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    // alert(data.message);
                    fetchQueues();
                })
                .catch(err => console.error(err));
            });
        });
    };

    bindActionWithConfirm('nextRegularBtn', "{{ route('users.nextRegular') }}", "Proceed to the next Regular queue?");
    bindActionWithConfirm('nextPriorityBtn', "{{ route('users.nextPriority') }}", "Proceed to the next Priority queue?");
    bindActionWithConfirm('skipBtn', "{{ route('users.skipQueue') }}", "Are you sure you want to skip this queue?");
    bindActionWithConfirm('recallBtn', "", "Are you sure you want to recall this queue?");
    bindActionWithConfirm('proceedBtn', "{{ route('users.proceedQueue') }}", "Proceed with the current queue?");

    updateButtonStates();
});







document.getElementById('recallBtn').addEventListener('click', () => {
    const servingQueueEl = document.getElementById('servingQueue');
    const queueNumber = servingQueueEl?.innerText.trim(); // e.g., A001
    const stepNumber = "{{ $stepNumber ?? 1 }}";
    const windowNumber = "{{ $windowNumber ?? 1 }}";

    if (!queueNumber) return;

    fetch("{{ route('api.manualRecall') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ queue_number: queueNumber, step_number: stepNumber, window_number: windowNumber })
    });
});

</script>


<style>
/* Tailwind modal transitions */
#confirmModal > div {
    @apply transform transition-all duration-150 ease-out opacity-0 scale-95;
}
#confirmModal.hidden > div {
    @apply opacity-0 scale-95;
}
#confirmModal:not(.hidden) > div {
    @apply opacity-100 scale-100;
}
</style>
@endsection

