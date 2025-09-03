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
        fetch(window.appRoutes.steps)
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
        fetch(window.appRoutes.latestTransaction)
            .then(res => res.json())
            .then(data => {
                if (!data || !data.id) return;
                if (lastAnnouncedId === data.id) return;
                lastAnnouncedId = data.id;

                const queueNum = data.queue_number;
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
