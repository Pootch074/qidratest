@extends('layouts.main')
@section('title', 'Encode')
@section('header')
@endsection

@section('content')
<div class="w-full h-[84vh] p-4 bg-[#cbdce8]">
    <div class="grid grid-cols-3 gap-2 h-full">
{{-- UPCOMING --}}
<div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
    <div class="bg-[#1a1172] text-white text-center font-bold py-2">UPCOMING</div>

    <div class="grid grid-cols-2 gap-4 p-2 bg-[#f5f8fd] flex-1">
        {{-- Regular Queue (Left) --}}
        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">REGULAR</div>
            <div id="upcoming-regular" class="flex-1 bg-[#f5f8fd] p-2 overflow-y-auto max-h-[70vh]">
                @forelse($regularQueues as $queue)
                    <div class="{{ $queue->style_class }} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold">
                        {{ $queue->formatted_number }}
                    </div>
                @empty
                    <div class="text-gray-400 text-center py-4">
                        No regular queues
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Priority Queue (Right) --}}
        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">PRIORITY</div>
            <div id="upcoming-priority" class="flex-1 bg-[#f5f8fd] p-2 overflow-y-auto max-h-[70vh]">
                @forelse($priorityQueues as $queue)
                    <div class="{{ $queue->style_class }} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold">
                        {{ $queue->formatted_number }}
                    </div>
                @empty
                    <div class="text-gray-400 text-center py-4">
                        No priority queues
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>




{{-- PENDING --}}
<div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
    <div class="bg-[#1a1172] text-white text-center font-bold py-2">
        PENDING
    </div>

    <div class="grid grid-cols-2 gap-4 p-2 bg-[#f5f8fd] flex-1">
        {{-- Regular Queue (Left) --}}
        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">REGULAR</div>
            <div id="pending-regular" class="flex-1 bg-[#f5f8fd] p-2 overflow-y-auto max-h-[70vh]">

                
                @forelse($pendingRegularQueues as $queue)
                    <div class="{{ $queue->style_class }} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold">
                        {{ $queue->formatted_number }}
                    </div>
                @empty
                    <div class="text-gray-400 text-center py-4">
                        No regular pending queues
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Priority Queue (Right) --}}
        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">PRIORITY</div>
            <div id="pending-priority" class="flex-1 bg-[#f5f8fd] p-2 overflow-y-auto max-h-[70vh]">
                @forelse($pendingPriorityQueues as $queue)
                    <div class="{{ $queue->style_class }} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold">
                        {{ $queue->formatted_number }}
                    </div>
                @empty
                    <div class="text-gray-400 text-center py-4">
                        No priority pending queues
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>



        {{-- SERVING --}}
        <div class="w-full flex flex-col bg-white rounded-md shadow overflow-hidden">

            {{-- Top Bar: Step & Window --}}
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">
                @if($stepNumber || $windowNumber)
                STEP {{ $stepNumber ?? '-' }}
                WINDOW {{ $windowNumber ?? '-' }}
            @endif
            </div>

            {{-- Field Office / Division / Section Info --}}
            <div class="bg-[#f5f8fd] p-4 text-center font-bold space-y-1 border-b border-gray-200">
                <p class="mb-0">{{ strtoupper($fieldOffice ?? '-') }}</p>
            <p class="mb-0">{{ strtoupper($divisionName ?? '-') }}</p>
            <p class="mb-0">{{ strtoupper($sectionName ?? '-') }}</p>
            </div>

            {{-- Currently Serving Queue --}}
            <div class="flex flex-col items-center justify-start bg-[#f0f4ff]">
                {{-- Label --}}
                <div class="w-full p-6 rounded-md text-center text-4xl font-bold mt-0">
                    Now Serving
                </div>

                {{-- Queue List --}}
                <div id="serving-queue" class="w-full p-6 rounded-md text-center text-4xl font-bold mt-0 overflow-y-auto max-h-[70vh]">
                    @forelse($servingQueue as $queue)
                        <div class="{{ $queue->style_class }} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold">
                            {{ $queue->formatted_number }}
                        </div>
                    @empty
                        <div class="text-gray-400 text-center py-4">
                            No serving queues
                        </div>
                    @endforelse
                </div>
            </div>

           {{-- Bottom Bar / Actions --}}
<div class="flex flex-col items-center bg-white p-4 border-t border-gray-200 space-y-3">
    {{-- Top Row: Next Regular + Next Priority --}}
    <div class="flex space-x-2">
        <button class="bg-blue-600 text-white px-5 text-xl py-2 rounded-md shadow">
            Next Regular
        </button>
        <button class="bg-red-600 text-white px-5 text-xl py-2 rounded-md shadow">
            Next Priority
        </button>
    </div>

    {{-- Bottom Row: Skip + Recall + Proceed --}}
    <div class="flex space-x-2">
        <button class="bg-gray-400 text-white px-5 text-xl py-2 rounded-md shadow">
            Skip
        </button>
        <button class="bg-gray-400 text-white px-5 text-xl py-2 rounded-md shadow">
            Recall
        </button>
        <button class="bg-gray-400 text-white px-5 text-xl py-2 rounded-md shadow">
            Proceed
        </button>
    </div>
</div>


            
        </div>

    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function renderQueue(list, container) {
    if (!list || list.length === 0) {
        $(container).html('<div class="text-gray-400 text-center py-4">No queues</div>');
    } else {
        let html = '';
        list.forEach(queue => {
            html += `<div class="${queue.style_class} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold">
                        ${queue.formatted_number}
                     </div>`;
        });
        $(container).html(html);
    }
}

function fetchQueues() {
    $.get("{{ route('queues.data') }}", function(data) {
        // Upcoming
        renderQueue(data.regularQueues, '#upcoming-regular');
        renderQueue(data.priorityQueues, '#upcoming-priority');

        // Pending
        renderQueue(data.pendingRegular, '#pending-regular');
        renderQueue(data.pendingPriority, '#pending-priority');

        // Serving
        renderQueue(data.servingQueue, '#serving-queue');

        // Optional: Update step, window, division, section, and field office info
        if(data.userInfo) {
            $('#step-number').text(data.userInfo.stepNumber);
            $('#window-number').text(data.userInfo.windowNumber);
            $('#section-name').text(data.userInfo.sectionName);
            $('#division-name').text(data.userInfo.divisionName);
            $('#field-office').text(data.userInfo.fieldOffice);
        }
    });
}

// Initial load
fetchQueues();

// Refresh every 5 seconds
setInterval(fetchQueues, 1000);
</script>



