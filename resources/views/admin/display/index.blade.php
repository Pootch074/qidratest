@extends('layouts.display')
@section('title', 'Display')
@section('header')
<style>
    @keyframes highlightFlash {
    0% { background-color: #ffff00; color: #000; transform: scale(1); }
    50% { background-color: #ff0000; color: #fff; transform: scale(1.2); }
    100% { background-color: #ffffff; color: #000; transform: scale(1); }
    }

    .queue-highlight {
        animation: highlightFlash 1s ease-in-out 2; /* repeat twice */
    }

</style>
@endsection

@section('content')
<div class="w-full h-[84vh] flex flex-col md:flex-row">
    <div class="md:w-7/12 w-full bg-[#2e3192] p-3 flex flex-col h-full">
        {{-- <div id="stepsContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4 w-full"> --}}
        <div id="stepsContainer" class="flex flex-col w-full h-full justify-start">
        </div>

        <div id="noSteps" class="hidden text-white text-lg font-medium">
            No steps available for your section.
        </div>
    </div>

    <div class="md:w-5/12 w-full bg-gray-800 text-white p-2 flex flex-col justify-start h-full">
        <!-- Date & Time -->
        <div class="w-full text-center">
            <p id="current-date" class="text-1xl md:text-2xl font-semibold mb-2"></p>
            <p id="current-time" class="text-2xl md:text-3xl font-bold"></p>
        </div>

        <div class="w-full flex flex-col items-center mt-5 space-y-4">
            <div class="w-full md:w-full">
                <video id="customVideo" class="w-full rounded-lg shadow-lg" autoplay muted loop>
                    <source src="{{ asset('assets/videos/dswd.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="flex flex-col items-center space-y-2 bg-gray-800 bg-opacity-80 p-4 rounded-md w-full">
                <div class="flex justify-center space-x-4">
                    <button id="volDown" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Vol -</button>
                    <button id="volMute" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Mute</button>
                    <button id="volUp" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Vol +</button>
                </div>
                <div class="w-full h-2 bg-gray-600 rounded overflow-hidden">
                    <div id="volBar" class="h-full bg-green-500 w-0"></div>
                </div>
                <span id="volPercent" class="text-white text-sm font-medium mt-1">0%</span>
            </div>
        </div>

        
    </div>
</div>


@endsection

@section('scripts')
{{-- Pass Laravel routes into JS --}}
<script>
    window.appRoutes = {
        steps: "{{ url('/steps') }}",
        latestTransaction: "{{ url('/display/transactions/latest') }}"
    };
</script>

@vite('resources/js/display.js')
@endsection