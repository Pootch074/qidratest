@extends('layouts.display')
@section('title', 'Display')
@section('header')
@endsection

@section('content')
<div class="w-full h-[84vh] flex flex-col md:flex-row">
    <!-- Left Section: Queue Information Panel -->
    <div class="md:w-1/2 w-full bg-blue-400 p-6 flex flex-col justify-start items-start space-y-6 h-full">
        <h2 class="text-2xl font-bold text-white">Steps</h2>

        <!-- Steps container -->
        <div id="stepsContainer" class="flex flex-col space-y-4 w-full"></div>

        <!-- Fallback message -->
        <div id="noSteps" class="hidden text-white text-lg font-medium">
            No steps available for your section.
        </div>
    </div>

    <!-- Right Section: Media & Info Panel -->
    <div class="md:w-1/2 w-full bg-[#1f2937] text-white p-6 flex flex-col justify-between h-full">
        <!-- Now Serving -->
        <div class="mt-6 w-full">
            <h2 class="text-2xl font-bold text-white">Now Serving</h2>
            <div id="nowServing" class="text-3xl font-extrabold text-yellow-300 mt-4">
                Waiting for next client...
            </div>
        </div>

        <!-- Video Section -->
        <div class="w-full flex flex-col items-center mb-3 space-y-4">
            <div class="w-full md:w-5/5">
                <video id="customVideo" class="w-full rounded-lg shadow-lg" autoplay muted loop>
                    <source src="{{ asset('assets/videos/dswd.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <!-- Controls Section -->
            <div class="flex flex-col items-center space-y-2 bg-gray-800 bg-opacity-80 p-4 rounded-md w-full md:w-4/5">
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

        <!-- Date & Time -->
        <div class="w-full text-center">
            <p id="current-date" class="text-1xl md:text-2xl font-semibold mb-2"></p>
            <p id="current-time" class="text-2xl md:text-3xl font-bold"></p>
        </div>
    </div>
</div>

{{-- Pass Laravel routes into JS --}}
<script>
    window.appRoutes = {
        steps: "{{ url('/steps') }}",
        latestTransaction: "{{ url('/display/transactions/latest') }}"
    };
</script>

{{-- Include external JS --}}
<script src="{{ asset('js/display.js') }}"></script>
@endsection
