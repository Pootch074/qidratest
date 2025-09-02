@extends('layouts.display')
@section('title', 'Display')
@section('header')
@endsection

@section('content')
<div class="w-full h-[84vh] flex flex-col md:flex-row">
    <!-- Left Section: Queue Information Panel -->
    <div class="md:w-1/2 w-full bg-blue-200 p-6 flex flex-col justify-center items-start space-y-8 h-full">
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
        <div class="w-full flex justify-center mb-6">
            <video class="w-full md:w-4/5 rounded-lg shadow-lg" autoplay muted loop>
                <source src="{{ asset('videos/sample.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <!-- Date & Time -->
        <div class="w-full text-center">
            <p id="current-date" class="text-2xl md:text-3xl font-semibold mb-2"></p>
            <p id="current-time" class="text-4xl md:text-5xl font-bold"></p>
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
@endsection
