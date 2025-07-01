export default function remarksEditor({ route, initialContent, period_id, lgu_id, questionnaire_id, user_id }) {
    return {
        editorEl: null,

        init() {
            const waitForQuill = () => {
                const quill = window._quillEditors?.['remarks'];
                if (quill) {
                    quill.root.innerHTML = initialContent;
                } else {
                    setTimeout(waitForQuill, 50);
                }
            };

            waitForQuill();
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