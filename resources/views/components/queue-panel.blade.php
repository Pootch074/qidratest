<div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
    <div class="bg-[#1a1172] text-white text-center font-bold py-2">{{ $title }}</div>

    <div class="grid grid-cols-2 gap-4 p-2 bg-[#f5f8fd] flex-1">
        {{-- Regular --}}
        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">REGULAR</div>
            <div id="{{ $regularId }}" class="flex-1 bg-[#f5f8fd] p-2 overflow-y-auto max-h-[70vh]">
                @forelse($regularQueues as $queue)
                    <x-queue-badge :queue="$queue" />
                @empty
                    <div class="text-gray-400 text-center py-4">No regular queues</div>
                @endforelse
            </div>
        </div>

        {{-- Priority --}}
        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">PRIORITY</div>
            <div id="{{ $priorityId }}" class="flex-1 bg-[#f5f8fd] p-2 overflow-y-auto max-h-[70vh]">
                @forelse($priorityQueues as $queue)
                    <x-queue-badge :queue="$queue" />
                @empty
                    <div class="text-gray-400 text-center py-4">No priority queues</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
