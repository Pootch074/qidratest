@extends('layouts.display')
@section('title', 'Display')
@section('header')
@endsection

@section('content')
<div class="w-full h-[84vh] flex flex-col md:flex-row">
<!-- Left Section: Queue Information Panel -->
<div class="md:w-1/2 w-full bg-blue-400 p-6 flex flex-col justify-start items-start space-y-6 h-full">
    <h2 class="text-2xl font-bold text-white">Steps</h2>

    <!-- Steps container (grid-based) -->
    <div id="stepsContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
        <!-- JS will inject steps here -->
    </div>

    <!-- Fallback message -->
    <div id="noSteps" class="hidden text-white text-lg font-medium">
        No steps available for your section.
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


<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetch("{{ url('/steps') }}")
            .then(response => {
                    console.log("Raw response:", response);
                    return response.json();
                })
            .then(data => {
                const container = document.getElementById('stepsContainer');
                const noSteps = document.getElementById('noSteps');

                if (data.length === 0) {
                    noSteps.classList.remove('hidden');
                    return;
                }

            data.forEach(step => {
                const card = document.createElement('div');
                card.className = "bg-white rounded-lg shadow-md p-4 flex flex-col";

                // Step info
                let html = `
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        Step ${step.step_number}
                    </h3>
                    <p class="text-gray-600 text-sm mb-3">${step.step_name}</p>
                `;

                // Windows
                if (step.windows.length > 0) {
                    html += `<div class="space-y-2">`;
                    step.windows.forEach(win => {
                        html += `
                            <div class="px-3 py-2 bg-blue-100 rounded text-gray-700 text-sm">
                                Window ${win.window_number}
                            </div>
                        `;
                    });
                    html += `</div>`;
                } else {
                    html += `<p class="text-gray-400 italic text-sm">No windows assigned</p>`;
                }

                card.innerHTML = html;
                container.appendChild(card);
            });






            })
            .catch(err => {
                console.error("Error fetching steps:", err);
                document.getElementById('noSteps').textContent = "Error loading steps.";
                document.getElementById('noSteps').classList.remove('hidden');
            });
    });
</script>
@endsection
