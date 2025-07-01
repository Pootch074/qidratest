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
            this.checked = level_id;

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
            .then(data => console.log('Level selected:', data))
            .catch(error => console.error('Error:', error));
        }
    };
}