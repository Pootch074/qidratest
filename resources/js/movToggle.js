import nProgress from "nprogress";

export default function movToggle({ route, user_id, period_id, questionnaire_id, lgu_id, mov_id, initialChecked = false }) {
    return {
        checked: initialChecked,
        toggle() {
            this.checked = !this.checked;

            nProgress.configure({ showSpinner: false });
            nProgress.start();

            fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    user_id,
                    period_id,
                    questionnaire_id,
                    lgu_id,
                    mov_id,
                    is_checked: this.checked
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                
                const statusEl = document.querySelector(`[data-questionnaire="${questionnaire_id}"] .status`);
                const anyMovSelected = document.querySelectorAll(
                    `input[type="checkbox"][data-questionnaire="${questionnaire_id}"]:checked`
                ).length > 0;

                const el = document.querySelector(`div[data-questionnaire="${questionnaire_id}"]`);
                const alpineData = Alpine.$data(el);
                const levelSelected = !!alpineData?.checked;
                console.log('levelSelected:', levelSelected);

                if (statusEl) {
                    if (levelSelected && anyMovSelected) {
                        statusEl.classList.remove('pending', 'inprogress');
                        statusEl.classList.add('completed');
                    } else if (levelSelected || anyMovSelected) {
                        statusEl.classList.remove('pending', 'completed');
                        statusEl.classList.add('inprogress');
                    } else {
                        statusEl.classList.remove('inprogress', 'completed');
                        statusEl.classList.add('pending');
                    }
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => nProgress.done());
        }
    }
}