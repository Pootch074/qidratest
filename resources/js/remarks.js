export default function remarksEditor({ route, initialContent, period_id, lgu_id, questionnaire_id, user_id }) {
    return {
        editorEl: null,

        init() {
            // Delay setting content until Quill is initialized
            this.editorEl = document.querySelector('#remarks .ql-editor');

            if (this.editorEl && initialContent) {
                this.editorEl.innerHTML = initialContent;
            }
        },

        save() {
            const content = this.editorEl ? this.editorEl.innerHTML : '';

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
                    remarks: content
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Remarks saved successfully.');
                } else {
                    alert('Failed to save.');
                }
            })
            .catch(() => alert('Error saving remarks.'));
        }
    }
}