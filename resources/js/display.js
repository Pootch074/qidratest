document.addEventListener("DOMContentLoaded", () => {
    const alertAudio = new Audio(window.alertAudioUrl);

    /** ---------------- Date & Time ---------------- **/
    function updateDateTime() {
        const now = new Date();
        const optionsDate = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        const dateEl = document.getElementById("current-date");
        const timeEl = document.getElementById("current-time");

        if (dateEl)
            dateEl.textContent = now.toLocaleDateString(undefined, optionsDate);
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
    let announcedTransactions = new Map();
    let speechQueue = [];
    let speaking = false;

    /** ✅ Load voices properly (fix for Chrome delay) **/
    function loadVoices() {
        function setVoices() {
            availableVoices = window.speechSynthesis.getVoices();
            if (availableVoices.length) {
                console.log("✅ Voices loaded:", availableVoices.length);
            }
        }

        window.speechSynthesis.onvoiceschanged = setVoices;
        setVoices();
    }
    loadVoices();

    /** ✅ Add message to speech queue **/
    function announce(formattedQueue, stepNumber, windowNumber, repeat) {
        const message = `Client number ${formattedQueue}, please proceed to step ${stepNumber}, window ${windowNumber}.`;
        speechQueue.push({ message, repeat });

        if (!speaking) {
            speakNext();
        }
    }

    /** ✅ Sequential speech processor **/
    function speakNext() {
        if (speechQueue.length === 0) {
            speaking = false;
            return;
        }

        speaking = true;
        const { message, repeat } = speechQueue.shift();
        let count = 0;

        const playAlertThenSpeak = () => {
            alertAudio.currentTime = 0;

            alertAudio.onended = () => {
                setTimeout(speakMessage, 400); // small delay to stabilize playback
            };

            alertAudio.play().catch((err) => {
                console.error("Failed to play alert:", err);
                setTimeout(speakMessage, 400);
            });
        };

        const speakMessage = () => {
            if (count >= repeat) {
                speakNext();
                return;
            }

            const utterance = new SpeechSynthesisUtterance(message);
            utterance.lang = "en-US";
            utterance.rate = 0.85;
            utterance.pitch = 1;
            utterance.volume = 1;

            // Use selected voice if available
            utterance.voice =
                availableVoices.length > voiceIndex
                    ? availableVoices[voiceIndex]
                    : availableVoices[0] || null;

            utterance.onend = () => {
                count++;
                if (count < repeat) {
                    setTimeout(speakMessage, 300);
                } else {
                    speakNext();
                }
            };

            // ✅ Prevent speechSynthesis from getting stuck
            window.speechSynthesis.cancel();

            // ✅ Actually speak
            window.speechSynthesis.speak(utterance);
        };

        playAlertThenSpeak();
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
                    card.className =
                        "rounded-lg shadow-md p-1 mb-3 flex flex-col bg-gray-200";

                    const stepNameDisplay =
                        step.step_name && step.step_name !== "None"
                            ? step.step_name
                            : "";

                    let html = `
                        <h3 class="text-4xl font-bold text-[#000000] mb-1 py-3 flex items-center justify-center space-x-2 bg-white rounded">
                            <span>STEP ${step.step_number}</span>
                            ${
                                stepNameDisplay
                                    ? `<span>${stepNameDisplay}</span>`
                                    : ""
                            }
                        </h3>
                    `;

                    if (step.windows.length > 0) {
                        html += `<div class="grid grid-cols-2 gap-2 ">`;

                        step.windows.forEach((win) => {
                            let firstTx =
                                win.transactions?.length > 0
                                    ? win.transactions[0]
                                    : null;

                            // ✅ Default background
                            let bgClass = "bg-[#2e3192]";

                            // ✅ Apply red background if step = 1 or 2 AND current user category = "priority"
                            if (
                                (step.step_number === 1 ||
                                    step.step_number === 2) &&
                                window.appUser.assignedCategory.toLowerCase() ===
                                    "priority"
                            ) {
                                bgClass = "bg-red-600"; // Tailwind red
                            }

                            html += `
                                <div class="rounded-lg text-[#FFFFFF] text-2xl font-semibold flex flex-col items-center justify-center w-full">
                                    <div class="flex items-center w-full h-full rounded-lg border-4 border-[#2e3192]">
                                        <span class="${bgClass} py-1 text-center w-1/5">
                                            <p class="text-xl font-semibold">Window</p>
                                            <p class="text-5xl font-bold">${
                                                win.window_number
                                            }</p>
                                        </span>
                                        ${
                                            firstTx
                                                ? `<span class="queue-number flex items-center justify-center bg-[#FFFFFF] text-[#000000] px-3 py-1 text-6xl font-bold text-center w-4/5 h-full rounded-r-lg" data-queue="${firstTx.queue_number}">
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
                if (!data || !data.length) return; // check array

                data.forEach((tx) => {
                    const key = `${tx.id}-${tx.step_number}`;
                    const lastAnnounced = announcedTransactions.get(key) ?? {
                        recall_count: null,
                        spokenCount: 0,
                    };

                    let repeatTimes;

                    if (tx.recall_count == null || tx.recall_count === 0) {
                        // Normal transaction → speak twice if not yet spoken twice
                        repeatTimes = 2 - lastAnnounced.spokenCount;
                        if (repeatTimes <= 0) return;
                    } else {
                        // Recall transaction → speak once if recall_count changed
                        if (lastAnnounced.recall_count === tx.recall_count)
                            return;
                        repeatTimes = 1;
                    }

                    const formattedQueue =
                        tx.client_type?.charAt(0).toUpperCase() +
                        String(tx.queue_number).padStart(3, "0");

                    announce(
                        formattedQueue,
                        tx.step_number,
                        tx.window_number,
                        repeatTimes
                    );

                    announcedTransactions.set(key, {
                        recall_count: tx.recall_count,
                        spokenCount:
                            (lastAnnounced.spokenCount || 0) + repeatTimes,
                    });
                });
            })
            .catch((err) => console.error("Error fetching transactions:", err));
    }

    /** ---------------- Initial Load + Intervals ---------------- **/
    fetchSteps();
    fetchLatestTransaction();
    setInterval(fetchSteps, 1000);
    setInterval(fetchLatestTransaction, 1000);
});
