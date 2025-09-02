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
                @if(session()->has('window_number') || session()->has('step_number'))
                    STEP {{ session('step_number') }}
                    WINDOW {{ session('window_number') }}
                @endif
            </div>

            {{-- Field Office / Division / Section Info --}}
            <div class="bg-[#f5f8fd] p-4 text-center font-bold space-y-1 border-b border-gray-200">
                <p class="mb-0">{{ strtoupper(session('field_office')) }}</p>
                <p class="mb-0">{{ strtoupper(session('division_name')) }}</p>
                <p class="mb-0">{{ strtoupper(session('section_name')) }}</p>
            </div>

            {{-- Currently Serving Queue --}}
            <div class="flex flex-col items-center justify-start bg-[#f0f4ff]">
                {{-- Serving Queue Label / Tech Info --}}
                <div class="w-full p-6 bg-yellow-100 border-2 border-yellow-400 rounded-md text-center text-4xl font-bold mt-0">
                    Serving Queue
                </div>

                {{-- Actual Queue Number --}}
                <div class="w-full p-6 bg-yellow-100 border-2 border-yellow-400 rounded-md text-center text-4xl font-bold mt-0">
                    @forelse($servingQueue as $queue)
                        <div class="bg-white p-2 my-1 rounded shadow text-center font-bold">
                            {{ $queue->lfgofkf }}
                        </div>
                    @empty
                        <div class="text-gray-400 text-center py-4">
                            No serving queues
                        </div>
                    @endforelse
                </div>
            </div>


            {{-- Bottom Bar --}}
            <div class="flex items-center justify-between bg-white p-2 border-t border-gray-200">

                {{-- Buttons Component --}}
                <div class="flex space-x-2">
                    <button class="bg-red-600 text-white px-4 py-2 rounded-md flex items-center justify-center shadow">
                        <i class="fas fa-users"></i>
                    </button>
                    <button class="bg-gray-400 text-white px-4 py-2 rounded-md shadow">
                        <i class="fas fa-backward"></i>
                    </button>
                    <button class="bg-gray-400 text-white px-4 py-2 rounded-md shadow">
                        <i class="fas fa-volume-up"></i>
                    </button>
                    <button class="bg-gray-400 text-white px-4 py-2 rounded-md shadow">
                        <i class="fas fa-check"></i>
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
        if (list.length === 0) {
            $(container).html('<div class="text-gray-400 text-center py-4">No queues</div>');
        } else {
            let html = '';
            list.forEach(queue => {
                html += `
                    <div class="${queue.style_class} text-white text-2xl p-2 my-1 rounded shadow text-center font-bold">
                        ${queue.formatted_number}
                    </div>`;
            });
            $(container).html(html);
        }
    }

    function fetchQueues() {
        $.get("{{ route('queues.data') }}", function(data) {
            renderQueue(data.regularQueues, '#upcoming-regular');
            renderQueue(data.priorityQueues, '#upcoming-priority');
            renderQueue(data.pendingRegular, '#pending-regular');
            renderQueue(data.pendingPriority, '#pending-priority');
        });
    }

    // Fetch initially and then every 5 seconds
    fetchQueues();
    setInterval(fetchQueues, 1000);
</script>

