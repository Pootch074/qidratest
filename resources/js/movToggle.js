export default function movToggle({ route, user_id, period_id, questionnaire_id, lgu_id, mov_id, initialChecked = false }) {
    return {
        checked: initialChecked,
        toggle() {
            this.checked = !this.checked;

            fetch(route, { // Replace with your actual route if not injected
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
            .then(data => console.log('Success:', data))
            .catch(error => console.error('Error:', error));
        }
    }
}