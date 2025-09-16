document.addEventListener("DOMContentLoaded", () => {
    let lastAnnouncedPerWindow = {};
    let announcedTransactions = new Set();

    /** ---------------- Date & Time ---------------- **/
    function updateDateTime() {
        const now = new Date();
        const optionsDate = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
        const dateEl = document.getElementById("current-date");
        const timeEl = document.getElementById("current-time");

        if (dateEl) dateEl.textContent = now.toLocaleDateString(undefined, optionsDate);
        if (timeEl) timeEl.textContent = now.toLocaleTimeString();
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
    const step = 0.1;

    function updateVolumeDisplay() {
        if (!video || !volBar || !volPercent) return;
        const volume = video.muted ? 0 : video.volume;
        volBar.style.width = volume * 100 + "%";
        volPercent.textContent = Math.round(volume * 100) + "%";
    }

    if (video) {
        video.loop = true;
        updateVolumeDisplay();

        volUp?.addEventListener("click", () => {
            video.volume = Math.min(video.volume + step, 1);
            if (video.volume > 0) video.muted = false;
            updateVolumeDisplay();
        });

        volDown?.addEventListener("click", () => {
            video.volume = Math.max(video.volume - step, 0);
            if (video.volume === 0) video.muted = true;
            updateVolumeDisplay();
        });

        volMute?.addEventListener("click", () => {
            video.muted = !video.muted;
            updateVolumeDisplay();
        });
    }

    /** ---------------- Speech Announcement ---------------- **/
    let voiceIndex = 2;
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

    function announce(formattedQueue, stepNumber, windowNumber, repeat = 2) {
        const message = `Client number ${formattedQueue}, please proceed to step ${stepNumber} window ${windowNumber}.`;
        for (let i = 0; i < repeat; i++) {
            const utterance = new SpeechSynthesisUtterance(message);
            utterance.lang = "en-US";
            utterance.rate = 0.8;
            utterance.pitch = 1;
            utterance.volume = 1;

            const setVoiceAndSpeak = () => {
                utterance.voice =
                    availableVoices.length > voiceIndex
                        ? availableVoices[voiceIndex]
                        : availableVoices[0];
                window.speechSynthesis.speak(utterance);
            };

            if (!availableVoices.length) {
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
                const noSteps = document.getElementById("noSteps");
                if (!container || !noSteps) return;

                container.innerHTML = "";
                if (!data || !data.length) {
                    noSteps.classList.remove("hidden");
                    return;
                }
                noSteps.classList.add("hidden");

                data.forEach((step) => {
                    const card = document.createElement("div");
                    card.className = "rounded-lg shadow-md p-1 mb-3 flex flex-col bg-gray-200";

                    const stepNameDisplay =
                        step.step_name && step.step_name !== "None" ? step.step_name : "";

                    let html = `
                        <h3 class="text-3xl font-bold text-[#000000] mb-1 flex items-center justify-center space-x-2 bg-white rounded">
                            <span>STEP ${step.step_number}</span>
                            ${stepNameDisplay ? `<span>${stepNameDisplay}</span>` : ""}
                        </h3>
                    `;

                    if (step.windows.length > 0) {
                        html += `<div class="grid grid-cols-2 gap-2">`;

                        step.windows.forEach((win) => {
                            let firstTx = win.transactions?.length > 0 ? win.transactions[0] : null;

                            html += `
                                <div class="rounded-lg text-[#FFFFFF] text-2xl font-semibold flex flex-col items-center justify-center w-full">
                                    <div class="flex items-center w-full h-full rounded-lg border-4 border-[#2e3192]">
                                        <span class="bg-[#2e3192] px-3 py-1 text-center w-1/5">
                                            <p class="text-lg font-semibold">Window</p>
                                            <p class="text-4xl font-bold">${win.window_number}</p>
                                        </span>
                                        ${
                                            firstTx
                                                ? `<span class="queue-number flex items-center justify-center bg-[#FFFFFF] text-[#000000] px-3 py-1 text-4xl font-bold text-center w-4/5 h-full rounded-r-lg" data-queue="${firstTx.queue_number}">
                                                        ${firstTx.queue_number}
                                                </span>`
                                                : `<span class="flex items-center justify-center bg-[#FFFFFF] text-[#000000] px-3 py-1 text-sm text-center w-4/5 h-full rounded-r-lg">🚫</span>`
                                        }
                                    </div>
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
                if (announcedTransactions.has(data.id)) return;

                announcedTransactions.add(data.id);

                const formattedQueue =
                    data.client_type?.charAt(0).toUpperCase() +
                    String(data.queue_number).padStart(3, "0");

                announce(formattedQueue, data.step_number, data.window_number);

                // Highlight removed
            })
            .catch((err) => console.error("Error fetching transactions:", err));
    }

    /** ---------------- Initial Load + Intervals ---------------- **/
    fetchSteps();
    fetchLatestTransaction();
    setInterval(fetchSteps, 1000);
    setInterval(fetchLatestTransaction, 1000);
});
