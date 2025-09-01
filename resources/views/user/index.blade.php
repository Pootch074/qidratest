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

    <div class="flex-1 bg-[#f5f8fd] overflow-y-auto p-2">
    @forelse($upcomingQueues as $queue)
        <div class="bg-white p-2 my-1 rounded shadow text-center font-bold">
            {{ $queue->sdsddses }}
        </div>
    @empty
        <div class="text-gray-400 text-center py-4">
            No pending queues
        </div>
    @endforelse
    </div>
</div>



{{-- PENDING --}}
<div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
    <div class="bg-[#1a1172] text-white text-center font-bold py-2">
        PENDING
    </div>
<div class="flex-1 bg-[#f5f8fd] overflow-y-auto p-2">
    @forelse($pendingQueues as $queue)
        <div class="bg-white p-2 my-1 rounded shadow text-center font-bold">
            {{ $queue->nchdfm }}
        </div>
    @empty
        <div class="text-gray-400 text-center py-4">
            No pending queues
        </div>
    @endforelse
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
            No pending queues
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
