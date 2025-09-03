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

<!-- Consolidated Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    let lastAnnouncedId = null;

    /** ---------------- Date & Time ---------------- **/
    function updateDateTime() {
        const now = new Date();
        const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('current-date').textContent = now.toLocaleDateString(undefined, optionsDate);
        document.getElementById('current-time').textContent = now.toLocaleTimeString();
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    /** ---------------- Video Volume Controls ---------------- **/
    const video = document.getElementById('customVideo');
    const volUp = document.getElementById('volUp');
    const volDown = document.getElementById('volDown');
    const volMute = document.getElementById('volMute');
    const volBar = document.getElementById('volBar');
    const volPercent = document.getElementById('volPercent');
    video.loop = true;
    const step = 0.1; // volume step

    function updateVolumeDisplay() {
        const volume = video.muted ? 0 : video.volume;
        volBar.style.width = (volume * 100) + '%';
        volPercent.textContent = Math.round(volume * 100) + '%';
    }
    volUp.addEventListener('click', () => {
        video.volume = Math.min(video.volume + step, 1);
        if (video.volume > 0) video.muted = false;
        updateVolumeDisplay();
    });
    volDown.addEventListener('click', () => {
        video.volume = Math.max(video.volume - step, 0);
        if (video.volume === 0) video.muted = true;
        updateVolumeDisplay();
    });
    volMute.addEventListener('click', () => {
        video.muted = !video.muted;
        updateVolumeDisplay();
    });
    updateVolumeDisplay();

    /** ---------------- Speech Announcement ---------------- **/
    function announce(queueNumber, stepNumber, windowNumber) {
        const message = `Client number ${queueNumber}, please proceed to step ${stepNumber} window ${windowNumber}.`;
        const utterance = new SpeechSynthesisUtterance(message);
        utterance.lang = 'en-US';
        window.speechSynthesis.speak(utterance);
    }

    /** ---------------- Fetch Steps ---------------- **/
    function fetchSteps() {
        fetch("{{ url('/steps') }}")
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('stepsContainer');
                container.innerHTML = "";
                const noSteps = document.getElementById('noSteps');

                if (!data || data.length === 0) {
                    noSteps.classList.remove('hidden');
                    return;
                }
                noSteps.classList.add('hidden');

                data.forEach(step => {
                    const card = document.createElement('div');
                    card.className = "bg-white rounded-lg shadow-md p-4 flex flex-col";

                    let html = `
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center space-x-2">
                            <span>Step ${step.step_number}</span>
                            <span class="text-gray-600 text-l font-semibold">(${step.step_name})</span>
                        </h3>
                    `;

                    if (step.windows.length > 0) {
                        html += `<div class="space-y-2">`;
                        step.windows.forEach(win => {
                            html += `
                                <div class="px-3 py-2 bg-blue-100 rounded text-gray-700 text-sm">
                                    <div class="font-semibold">Window ${win.window_number}</div>
                            `;
                            if (win.transactions && win.transactions.length > 0) {
                                html += `<ul class="mt-1 space-y-1">`;
                                win.transactions.forEach(tx => {
                                    html += `
                                        <li class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                            ${tx.queue_number}
                                        </li>
                                    `;
                                });
                                html += `</ul>`;
                            } else {
                                html += `<p class="text-gray-400 italic text-xs mt-1">No transactions</p>`;
                            }
                            html += `</div>`;
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
    }

    /** ---------------- Fetch Latest Transaction ---------------- **/
    function fetchLatestTransaction() {
        fetch("{{ url('/display/transactions/latest') }}")
            .then(res => res.json())
            .then(data => {
                if (!data || !data.id) return;
                if (lastAnnouncedId === data.id) return;
                lastAnnouncedId = data.id;

                const queueNum = data.queue_number; // already prefixed by backend
                const stepNum = data.step_number;
                const windowNum = data.window_number;

                document.getElementById('nowServing').textContent =
                    `${queueNum} → Step ${stepNum}, Window ${windowNum}`;

                announce(queueNum, stepNum, windowNum);
            })
            .catch(err => console.error("Error fetching transactions:", err));
    }

    /** ---------------- Initial Load + Intervals ---------------- **/
    fetchSteps();
    fetchLatestTransaction();
    setInterval(fetchSteps, 1000);
    setInterval(fetchLatestTransaction, 1000);
});
</script>
@endsection
