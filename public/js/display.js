document.addEventListener("DOMContentLoaded", () => {
    let lastAnnouncedId = null;
    let lastAnnouncedPerWindow = {}; // track last announced transaction per window

    /** ---------------- Date & Time ---------------- **/
    function updateDateTime() {
        const now = new Date();
        const optionsDate = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        document.getElementById("current-date").textContent =
            now.toLocaleDateString(undefined, optionsDate);
        document.getElementById("current-time").textContent =
            now.toLocaleTimeString();
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    /** ---------------- Video Volume Controls ---------------- **/
    const video = document.getElementById("customVideo");
    const volUp = document.getElementById("volUp");
    const volDown = document.getElementById("volDown");
    const volMute = document.getElementById("volMute");
    const volBar = document.getElementById("volBar");
    const volPercent = document.getElementById("volPercent");
    video.loop = true;
    const step = 0.1; // volume step

    function updateVolumeDisplay() {
        const volume = video.muted ? 0 : video.volume;
        volBar.style.width = volume * 100 + "%";
        volPercent.textContent = Math.round(volume * 100) + "%";
    }
    volUp.addEventListener("click", () => {
        video.volume = Math.min(video.volume + step, 1);
        if (video.volume > 0) video.muted = false;
        updateVolumeDisplay();
    });
    volDown.addEventListener("click", () => {
        video.volume = Math.max(video.volume - step, 0);
        if (video.volume === 0) video.muted = true;
        updateVolumeDisplay();
    });
    volMute.addEventListener("click", () => {
        video.muted = !video.muted;
        updateVolumeDisplay();
    });
    updateVolumeDisplay();

    /** ---------------- Speech Announcement ---------------- **/
    let voiceIndex = 2; // <-- change this to select a different voice like in your old system
    let availableVoices = [];

    function loadVoices() {
        availableVoices = window.speechSynthesis.getVoices();
        if (!availableVoices.length) {
            window.speechSynthesis.onvoiceschanged = () => {
                availableVoices = window.speechSynthesis.getVoices();
            };
        }
    }
    loadVoices();

    function announce(formattedQueue, stepNumber, windowNumber, repeat = 1) {
        const message = `Client number ${formattedQueue}, please proceed to step ${stepNumber} window ${windowNumber}.`;

        for (let i = 0; i < repeat; i++) {
            const utterance = new SpeechSynthesisUtterance(message);
            utterance.lang = "en-US";
            utterance.rate = 0.8;
            utterance.pitch = 1;
            utterance.volume = 1;

            const setVoiceAndSpeak = () => {
                if (availableVoices.length > voiceIndex) {
                    utterance.voice = availableVoices[voiceIndex];
                } else if (availableVoices.length > 0) {
                    utterance.voice = availableVoices[0]; // fallback
                }
                window.speechSynthesis.speak(utterance);
            };

            if (availableVoices.length === 0) {
                window.speechSynthesis.onvoiceschanged = setVoiceAndSpeak;
            } else {
                setVoiceAndSpeak();
            }
        }
    }

    /** ---------------- Fetch Steps ---------------- **/
    function fetchSteps() {
        fetch(window.appRoutes.steps)
            .then((response) => response.json())
            .then((data) => {
                const container = document.getElementById("stepsContainer");
                container.innerHTML = "";
                const noSteps = document.getElementById("noSteps");

                if (!data || data.length === 0) {
                    noSteps.classList.remove("hidden");
                    return;
                }
                noSteps.classList.add("hidden");

                data.forEach((step) => {
    const card = document.createElement("div");
    card.className = "bg-white rounded-lg shadow-md p-2 flex flex-col";

    // Only display valid step names
    const stepNameDisplay =
        step.step_name && step.step_name !== "None" ? step.step_name : "";

    let html = `
        <h3 class="text-3xl font-bold text-[#000000] mb-2 flex items-center justify-center space-x-2">
            <span>Step ${step.step_number}</span>
            ${stepNameDisplay ? `<span>${stepNameDisplay}</span>` : ""}
        </h3>
    `;

    if (step.windows.length > 0) {
        html += `<div class="grid grid-cols-2 gap-2 bg-pink-500">`;

        step.windows.forEach((win) => {
            html += `
                <div class="px-3 py-2 bg-[#2e3192] rounded text-[#FFFFFF] text-2xl">
                    <div class="font-semibold">Window ${win.window_number}</div>
            `;

            if (win.transactions && win.transactions.length > 0) {
                html += `<ul class="mt-1 space-y-1">`;
                win.transactions.forEach((tx) => {
                    html += `
                        <li class="bg-[#FFFFFF] text-[#000000] px-2 py-1 rounded text-5xl font-bold text-center">
                            ${tx.queue_number} 
                        </li>
                    `;
                });
                html += `</ul>`;
            } else {
                html += `<p class="text-gray-1000 italic text-xs mt-1">Currently idle</p>`;
            }

            html += `</div>`;

            // Speech synthesis logic remains unchanged
            if (win.transactions && win.transactions.length > 0) {
                const tx = win.transactions[0];
                if (lastAnnouncedPerWindow[win.window_id] !== tx.id) {
                    lastAnnouncedPerWindow[win.window_id] = tx.id;
                    announce(tx.queue_number, step.step_number, win.window_number);
                }
            }
        });

        html += `</div>`;
    } else {
        html += `<p class="text-gray-400 italic text-sm">No windows assigned</p>`;
    }

    card.innerHTML = html;
    container.appendChild(card);
});

            })
            .catch((err) => {
                console.error("Error fetching steps:", err);
                const noSteps = document.getElementById("noSteps");
                if (noSteps) {
                    noSteps.textContent = "Error loading steps.";
                    noSteps.classList.remove("hidden");
                }
            });
    }




    

    /** ---------------- Fetch Latest Transaction ---------------- **/
    function fetchLatestTransaction() {
        fetch(window.appRoutes.latestTransaction)
            .then((res) => res.json())
            .then((data) => {
                if (!data || !data.id) return;

                // Skip if already announced
                if (announcedTransactions.has(data.id)) return;

                // Mark as announced
                announcedTransactions.add(data.id);

                // Format queue number with prefix if needed
                const formattedQueue =
                    data.client_type?.charAt(0).toUpperCase() +
                    String(data.queue_number).padStart(3, "0");

                announce(formattedQueue, data.step_number, data.window_number);
            })
            .catch((err) => console.error("Error fetching transactions:", err));
    }

    /** ---------------- Initial Load + Intervals ---------------- **/
    fetchSteps();
    fetchLatestTransaction();
    setInterval(fetchSteps, 1000);
    setInterval(fetchLatestTransaction, 1000);
});
