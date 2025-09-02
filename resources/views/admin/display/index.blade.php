@extends('layouts.display')
@section('title', 'Display')
@section('header')
@endsection

@section('content')
<div class="w-full h-[84vh] flex flex-col md:flex-row">
    <!-- Left Section: Queue Information Panel -->
    <div class="md:w-1/2 w-full bg-blue-400 p-6 flex flex-col justify-center items-start space-y-8 h-full">
        <!-- Queue Number -->
        <div class="text-center md:text-left w-full">
            <h1 class="text-6xl md:text-8xl font-bold text-[#1f2937]">A-012</h1>
            <p class="mt-2 text-xl md:text-2xl text-gray-600">Now Serving</p>
        </div>

        <!-- Service Window -->
        <div class="w-full">
            <p class="text-2xl md:text-3xl font-semibold text-gray-700">Window: <span class="text-[#2563eb]">3</span></p>
        </div>

        <!-- Steps/Status -->
        <div class="w-full">
            <h2 class="text-xl md:text-2xl font-semibold mb-3 text-gray-800">Client Steps</h2>
            <ul class="space-y-2 overflow-y-auto max-h-[50vh]">
                <li class="flex justify-between items-center bg-gray-100 p-3 rounded-md shadow-sm">
                    <span>Step 1: Registration</span>
                    <span class="text-green-600 font-semibold">Completed</span>
                </li>
                <li class="flex justify-between items-center bg-gray-100 p-3 rounded-md shadow-sm">
                    <span>Step 2: Verification</span>
                    <span class="text-yellow-500 font-semibold">In Progress</span>
                </li>
                <li class="flex justify-between items-center bg-gray-100 p-3 rounded-md shadow-sm">
                    <span>Step 3: Processing</span>
                    <span class="text-gray-400 font-semibold">Pending</span>
                </li>
                <!-- Add more steps if needed -->
            </ul>
        </div>
    </div>

    <!-- Right Section: Media & Info Panel -->
    <div class="md:w-1/2 w-full bg-[#1f2937] text-white p-6 flex flex-col justify-between h-full">
        <!-- Video Section -->
        <div class="w-full flex flex-col items-center mb-3 space-y-4">
            <!-- Video Container -->
            <div class="w-full md:w-5/5">
                <video id="customVideo" class="w-full rounded-lg shadow-lg" autoplay muted loop>
                    <source src="{{ asset('assets/videos/dswd.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <!-- Controls Section (outside the video) -->
            <div class="flex flex-col items-center space-y-2 bg-gray-800 bg-opacity-80 p-4 rounded-md w-full md:w-4/5">
                <div class="flex justify-center space-x-4">
                    <button id="volDown" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Vol -</button>
                    <button id="volMute" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Mute</button>
                    <button id="volUp" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Vol +</button>
                </div>

                <!-- Volume Indicator -->
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

<!-- Script for Live Date & Time -->
<script>
    function updateDateTime() {
        const now = new Date();
        const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('current-date').textContent = now.toLocaleDateString(undefined, optionsDate);
        document.getElementById('current-time').textContent = now.toLocaleTimeString();
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

<script>
    const video = document.getElementById('customVideo');
    const volUp = document.getElementById('volUp');
    const volDown = document.getElementById('volDown');
    const volMute = document.getElementById('volMute');
    const volBar = document.getElementById('volBar');
    const volPercent = document.getElementById('volPercent');

    // Ensure video plays on loop without interruptions
    video.loop = true;

    // Volume step (10%)
    const step = 0.1;

    // Initialize volume display
    function updateVolumeDisplay() {
        const volume = video.muted ? 0 : video.volume;
        volBar.style.width = (volume * 100) + '%';
        volPercent.textContent = Math.round(volume * 100) + '%';
    }

    // Increase volume
    volUp.addEventListener('click', () => {
        video.volume = Math.min(video.volume + step, 1);
        if(video.volume > 0) video.muted = false;
        updateVolumeDisplay();
    });

    // Decrease volume
    volDown.addEventListener('click', () => {
        video.volume = Math.max(video.volume - step, 0);
        if(video.volume === 0) video.muted = true;
        updateVolumeDisplay();
    });

    // Mute/unmute toggle
    volMute.addEventListener('click', () => {
        video.muted = !video.muted;
        updateVolumeDisplay();
    });

    // Update display on page load
    updateVolumeDisplay();
</script>
@endsection
