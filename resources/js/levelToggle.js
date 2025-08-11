import nProgress from "nprogress";

export default function levelToggle({
    route,
    user_id,
    period_id,
    questionnaire_id,
    lgu_id,
    level_id,
    initialChecked = null
}) {
    return {
        checked: initialChecked,

        toggle() {
            nProgress.configure({ showSpinner: false });
            nProgress.start();

            // Remove 'selected' class from all other .option elements
            document.querySelectorAll('.option.selected').forEach(el => {
                el.classList.remove('selected');
            });

            // Add 'selected' class to the currently clicked .option
            this.$root.classList.add('selected');

            // Toggle level selection
            if (this.checked === level_id) {
                this.checked = null;
            } else {
                this.checked = level_id;
            }

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
                    level_id,
                    is_checked: level_id
                })
            })
            .then(res => res.json())
            .then(data => {
                const anyMovSelected = document.querySelectorAll(
                    `input[type="checkbox"][data-questionnaire="${questionnaire_id}"]:checked`
                ).length > 0;

                const remarksEl = document.getElementById('remarks');
                const hasRemarks = remarksEl && remarksEl.value !== "";

                const isChecked = this.checked;

                let counter = 0;
                if (isChecked) counter++;
                if (anyMovSelected) counter++;
                if (hasRemarks) counter++;

                const statusEl = document.querySelector(`[data-questionnaire="${questionnaire_id}"] .status`);
                if (statusEl) {
                    console.log('Counter:', counter);
                    console.log('Checked:', isChecked);
                    console.log('Any mov selected:', anyMovSelected);
                    console.log('Has remarks:', hasRemarks, remarksEl.value);

                    if (counter === 3) {
                        console.log('Status: completed');
                        statusEl.classList.remove('pending', 'inprogress');
                        statusEl.classList.add('completed');
                    } else if (counter === 2 || counter === 1) {
                        console.log('Status: inprogress');
                        statusEl.classList.remove('pending', 'completed');
                        statusEl.classList.add('inprogress');
                    } else {
                        console.log('Status: pending');
                        statusEl.classList.remove('inprogress', 'completed');
                        statusEl.classList.add('pending');
                    }
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => nProgress.done());
        }
    };
}
