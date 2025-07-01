export default function recommendationsEditor({ route, initialContent, period_id, lgu_id, questionnaire_id, user_id }) {
    return {
        save() {
            const editorEl = document.querySelector('#recommendations .ql-editor');
            const content = editorEl ? editorEl.innerHTML : '';

            fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    period_id,
                    lgu_id,
                    questionnaire_id,
                    user_id,
                    recommendations: content
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // alert('Recommendations saved successfully.');
                } else {
                    // alert('Failed to save.');
                }
            })
            .catch(() => alert('Error saving recommendations.'));
        }
    }
}