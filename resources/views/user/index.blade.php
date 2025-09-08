@extends('layouts.main')

@section('title', 'Encode')

@section('content')
<div class="w-full h-[84vh] p-4 bg-[#cbdce8]">
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
        <div class="w-full flex flex-col bg-white rounded-md shadow overflow-hidden">

            {{-- Step & Window --}}
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">
                @if($stepNumber || $windowNumber)
                    STEP {{ $stepNumber ?? '-' }} &nbsp; WINDOW {{ $windowNumber ?? '-' }}
                @endif
            </div>

            {{-- Office Info --}}
            <div class="bg-[#f5f8fd] p-4 text-center font-bold space-y-1 border-b border-gray-200">
                <p>{{ strtoupper($fieldOffice ?? '-') }}</p>
                <p>{{ strtoupper($divisionName ?? '-') }}</p>
                <p>{{ strtoupper($sectionName ?? '-') }}</p>
            </div>

            {{-- Now Serving --}}
            <div class="flex flex-col items-center bg-[#f0f4ff]">
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
            <div class="flex flex-col items-center bg-white p-4 border-t border-gray-200 space-y-3">
                <div class="flex space-x-2">
                    <button id="nextRegularBtn" class="bg-blue-600 text-white px-5 text-xl py-2 rounded-md shadow">Next Regular</button>
                    <button id="nextPriorityBtn" class="bg-red-600 text-white px-5 text-xl py-2 rounded-md shadow">Next Priority</button>
                </div>
                <div class="flex space-x-2">
    <button id="skipBtn" class="bg-gray-400 text-white px-5 text-xl py-2 rounded-md shadow">Skip</button>
    <button id="recallBtn" class="bg-gray-400 text-white px-5 text-xl py-2 rounded-md shadow">Recall</button>
    <button id="proceedBtn" class="bg-gray-400 text-white px-5 text-xl py-2 rounded-md shadow">Proceed</button>
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
            container.innerHTML = `<div class="text-gray-400 text-center py-4">No queues</div>`;
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
