@extends('layouts.main')

@section('title', 'Encode')

@section('content')
<div class="w-full h-[84vh] p-4 bg-gray-200">
    <div class="grid grid-cols-3 gap-2 h-full">

        {{-- UPCOMING --}}
        <x-queue-panel 
            title="UPCOMING" 
            regularId="upcoming-regular" 
            priorityId="upcoming-priority"
            :regularQueues="$regularQueues"
            :priorityQueues="$priorityQueues"
        />

        {{-- PENDING --}}
        <x-queue-panel 
            title="PENDING" 
            regularId="pending-regular" 
            priorityId="pending-priority"
            :regularQueues="$pendingRegularQueues"
            :priorityQueues="$pendingPriorityQueues"
        />

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
                <div id="serving-queue" class="w-full p-6 text-center text-4xl font-bold overflow-y-auto max-h-[70vh]">
                    @forelse($servingQueue as $queue)
                        <x-queue-badge :queue="$queue" />
                    @empty
                        <div class="text-gray-400 text-center py-4">No serving queues</div>
                    @endforelse
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col bg-white p-4 space-y-3 flex-1">
                <div class="flex space-x-2 w-full mb-5">
                    <button id="nextRegularBtn" 
                        class="flex-1 text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 
                        shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">
                        Next Regular
                    </button>

                    <button id="nextPriorityBtn" 
                        class="flex-1 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br 
                        focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 
                        shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">
                        Next Priority
                    </button>
                </div>

                <div class="flex space-x-2 w-full">
                    <button id="skipBtn" 
                        class="flex-1 text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br 
                        focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 
                        shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">
                        Skip
                    </button>

                    <button id="recallBtn" 
                        class="flex-1 text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 hover:bg-gradient-to-br 
                        focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 
                        shadow-lg shadow-orange-500/50 dark:shadow-lg dark:shadow-orange-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">
                        Recall
                    </button>

                    <button id="proceedBtn" 
                        class="flex-1 text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br 
                        focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 
                        shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 
                        font-medium rounded-lg text-sm py-2.5 text-center">
                        Proceed
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

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
                renderQueue(data.regularQueues, '#upcoming-regular');
                renderQueue(data.priorityQueues, '#upcoming-priority');
                renderQueue(data.pendingRegular, '#pending-regular');
                renderQueue(data.pendingPriority, '#pending-priority');
                renderQueue(data.servingQueue, '#serving-queue');
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
            .then(data => alert(data.message))
            .catch(err => console.error(err));
        });
    };

    bindAction('nextRegularBtn', "{{ route('users.nextRegular') }}");
    bindAction('nextPriorityBtn', "{{ route('users.nextPriority') }}");
    bindAction('skipBtn', "{{ route('users.skipQueue') }}");
    bindAction('proceedBtn', "{{ route('users.proceedQueue') }}");

});
</script>
@endsection
