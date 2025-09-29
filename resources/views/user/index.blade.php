@extends('layouts.main')

@section('title', 'Encode')

@section('content')
<div class="w-full h-[84vh] p-4 bg-gray-200">
    <div class="grid grid-cols-10 gap-2 h-full">
        <div class="col-span-3 flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">UPCOMING</div>

            <div class="grid grid-cols-3 gap-4 p-2 bg-white rounded-b-lg border-2 border-[#2e3192] flex-1">
                {{-- Regular --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">REGULAR</div>
                    <div id="upcomingRegu" class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh]">

                    </div>
                </div>

                {{-- Priority --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">PRIORITY</div>
                    <div id="upcomingPrio" class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh]">
                    </div>
                </div>

                {{-- Returnee --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">RETURNEE</div>
                    <div id="upcomingReturnee" class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh]">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-4 flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">PENDING</div>

            <div class="grid grid-cols-4 gap-4 p-2 bg-white rounded-b-lg border-2 border-[#2e3192] flex-1">
                {{-- Regular --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">REGULAR</div>
                    <div id="pendingRegu" class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh]">
                        {{-- Fetched Regular Queues --}}
                    </div>
                </div>

                {{-- Priority --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">PRIORITY</div>
                    <div id="pendingPrio" class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh]">
                    </div>
                </div>

                {{-- Returnee --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">RETURNEE</div>
                    <div id="pendingReturnee" class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh]">
                    </div>
                </div>

                {{-- Deferred --}}
                <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
                    <div class="bg-[#2e3192] text-white text-center font-bold py-2">DEFERRED</div>
                    <div id="deferred" class="flex-1 bg-white p-2 overflow-y-auto max-h-[68vh]">
                    </div>
                </div>

            </div>
        </div>


        {{-- SERVING --}} 
    <div class="col-span-3 flex flex-col rounded-md bg-white rounded-b-lg border-2 border-[#2e3192] shadow overflow-hidden h-[80vh]">
        <!-- Each child will auto get equal height -->
        <div class="bg-[#2e3192] text-white text-center font-bold text-2xl py-2">
            @if($stepNumber || $windowNumber)
                STEP {{ $stepNumber ?? '-' }}&nbsp;WINDOW {{ $windowNumber ?? '-' }}
            @endif
        </div>

        <div class="text-black text-center font-bold py-2">
            <p>{{ strtoupper($fieldOffice ?? '-') }}</p>
            <p>{{ strtoupper($divisionName ?? '-') }}</p>
            <p>{{ strtoupper($sectionName ?? '-') }}</p>
        </div>

        <div class="text-black text-center font-bold text-2xl py-2">
            Now Serving
        </div>

        <div id="servingQueue" class="text-white text-center font-bold flex-1">
        </div>

        <div class="content-center text-white text-center font-bold flex-1 px-2">

            <div class="flex space-x-2 w-full mb-5">
                    <button id="nextRegularBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 
                        shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Next Regular</button>
                    <button id="nextPriorityBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 
                        shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Next Priority</button>

                    <button id="returneeBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 
                        shadow-lg shadow-orange-500/50 dark:shadow-lg dark:shadow-orange-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Next Returnee</button>
                </div>
                <div class="flex space-x-2 w-full">
                    <button id="skipBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 
                        shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Skip</button>
                    <button id="recallBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 
                        shadow-lg shadow-orange-500/50 dark:shadow-lg dark:shadow-orange-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">Recall</button>
                    <button id="deferBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br 
                        focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-lg shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 
                        font-medium rounded-lg text-sm px-5 py-2.5 text-center">Defer</button>
                    <button id="proceedBtn" class="queue-btn flex-1 text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 
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
            modalMessage.textContent = "Are you sure you want to perform this action?";
            setBtnState(modalConfirmBtn, true);
        }, 150); // match transition
    };

    modalCancelBtn.addEventListener('click', hideModal);
    modalCloseBtn.addEventListener('click', hideModal);
    modalConfirmBtn.addEventListener('click', () => {
        if (currentAction) currentAction();
    });


    /*** === Button State Management === */
    function setBtnState(btn, enabled) {
        if (!btn) return;
        btn.disabled = !enabled;
        btn.classList.toggle('opacity-50', !enabled);
        btn.classList.toggle('cursor-not-allowed', !enabled);
    }

function updateButtonStates(data) {
    const servingEmpty = !data.servingQueue?.length;
    const reguEmpty = !data.upcomingRegu?.length;
    const prioEmpty = !data.upcomingPrio?.length;
    const returneeEmpty = !data.upcomingReturnee?.length;

    // Serving-dependent buttons
    setBtnState(document.getElementById('skipBtn'), !servingEmpty);
    setBtnState(document.getElementById('recallBtn'), !servingEmpty);
    setBtnState(document.getElementById('deferBtn'), !servingEmpty);
    setBtnState(document.getElementById('proceedBtn'), !servingEmpty);

    // Upcoming-based buttons
    // ✅ Disabled if (list empty) OR (something is already serving)
    setBtnState(
        document.getElementById('nextRegularBtn'),
        servingEmpty && !reguEmpty
    );
    setBtnState(
        document.getElementById('nextPriorityBtn'),
        servingEmpty && !prioEmpty
    );
    setBtnState(
        document.getElementById('returneeBtn'), 
        servingEmpty && !returneeEmpty
    );

}



    const renderQueue = (list, containerId) => {
    const container = document.querySelector(containerId);
    if (!container) return;

    if (!list?.length) {
        container.innerHTML = `<div class="text-gray-400 text-center py-4 text-sm">🚫Empty</div>`;
        return;
    }

container.innerHTML = list.map(queue => `
    <div class="${queue.style_class} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold cursor-pointer"
         data-id="${queue.id}">
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
            renderQueue(data.upcomingReturnee, '#upcomingReturnee');
            renderQueue(data.pendingRegu, '#pendingRegu');
            renderQueue(data.pendingPrio, '#pendingPrio');
            renderQueue(data.pendingReturnee, '#pendingReturnee');
            renderQueue(data.deferred, '#deferred');
            renderQueue(data.servingQueue, '#servingQueue');
            updateButtonStates(data); // ✅ pass queues
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
                setBtnState(modalConfirmBtn, false);
                fetch(routeName, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    modalMessage.textContent = data.message || "Action completed.";
                    fetchQueues();
                    setTimeout(hideModal, 1000);
                })
                .catch(err => {
                    console.error(err);
                    modalMessage.textContent = "❌ Something went wrong. Please try again.";
                    setBtnState(modalConfirmBtn, true);
                });
            });
        });
    };

    function bindPendingQueue(containerId, route, label) {
    document.addEventListener('click', e => {
        const target = e.target.closest(`${containerId} [data-id]`);
        if (target) {
            const id = target.getAttribute('data-id');

            showModal(`Update pending ${label} queue?`, () => {
                fetch(route, {
                    method: 'POST',  // ✅ make sure it’s POST
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // ✅ CSRF protection
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id }) // ✅ send transaction id
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    modalMessage.textContent = data.message || "✅ Action completed.";
                    fetchQueues(); // refresh queues
                    setTimeout(hideModal, 1000);
                })
                .catch(err => {
                    modalMessage.textContent = `❌ Something went wrong: ${err.message}`;
                    setBtnState(modalConfirmBtn, true);
                });
            });
        }
    });
}


    // Use it for all three
    bindPendingQueue('#pendingRegu', "{{ route('queues.updatePendingRegu') }}", "Regular");
    bindPendingQueue('#pendingPrio', "{{ route('queues.updatePendingPrio') }}", "Priority");
    bindPendingQueue('#pendingReturnee', "{{ route('queues.updatePendingReturnee') }}", "Returnee");


    bindActionWithConfirm('nextRegularBtn', "{{ route('users.nextRegular') }}", "Proceed to the next Regular queue?");
    bindActionWithConfirm('nextPriorityBtn', "{{ route('users.nextPriority') }}", "Proceed to the next Priority queue?");
    bindActionWithConfirm('returneeBtn', "{{ route('users.nextReturnee') }}", "Proceed to the next Returnee queue?");
    bindActionWithConfirm('skipBtn', "{{ route('users.skipQueue') }}", "Are you sure you want to skip this queue?");
    bindActionWithConfirm('recallBtn', "{{ route('users.recallQueue') }}", "Are you sure you want to recall this queue?");
    bindActionWithConfirm('deferBtn', "{{ route('users.returnQueue') }}", "Mark the current serving client as returnee?");
    bindActionWithConfirm('proceedBtn', "{{ route('users.proceedQueue') }}", "Proceed with the current queue?");

    
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

