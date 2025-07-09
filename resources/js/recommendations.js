import nProgress from "nprogress";

export default function recommendationsEditor({ route, initialContent, period_id, lgu_id, questionnaire_id, user_id }) {
    return {
        editorEl: null,

        init() {
            const waitForQuill = () => {
                const quill = window._quillEditors?.['recommendations'];
                if (quill) {
                    quill.root.innerHTML = initialContent;
                } else {
                    setTimeout(waitForQuill, 50);
                }
            };

            waitForQuill();
        },
        
        save() {
            const editorEl = document.querySelector('#recommendations .ql-editor');
            const content = editorEl ? editorEl.innerHTML : '';
            
            nProgress.configure({ showSpinner: false });
            nProgress.start();

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
            .finally(() => {
                nProgress.done();
            });
            // .catch(() => alert('Error saving recommendations.'));
        }
    }
}