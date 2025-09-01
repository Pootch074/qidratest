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



                {{-- @forelse(($upcoming ?? collect()) as $txn)
                    <div class="bg-white shadow rounded p-2 text-center font-semibold mb-2">
                        {{ $txn->queue_number }}
                    </div>
                @empty
                    <p class="text-center text-gray-500 italic mt-4">No upcoming</p>
                @endforelse --}}


                
            </div>
        </div>


        {{-- PENDING --}}
        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">
                PENDING
            </div>
            <div class="flex-1 bg-[#f5f8fd]"></div>
        </div>

        {{-- SERVING --}}
        <div class="flex flex-col bg-white rounded-md shadow overflow-hidden">
            <div class="bg-[#1a1172] text-white text-center font-bold py-2">
                SERVING STEP 2 WINDOW 2
            </div>

            <div class="flex-1 flex flex-col items-center justify-center bg-[#f5f8fd]">
                <div class="text-center font-bold space-y-1">
                    <p>DSWD FOXI</p>
                    <p>PROTECTIVE SERVICE DIVISION</p>
                    <p>CRISIS INTERVENTION SECTION</p>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="flex items-center justify-between bg-white p-2">
                {{-- Left Blue-Red Bar --}}
                <div class="flex-1 h-6 flex">
                    <div class="w-1/2 bg-[#1a1172]"></div>
                    <div class="w-1/2 bg-red-600"></div>
                </div>

                {{-- Buttons --}}
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
