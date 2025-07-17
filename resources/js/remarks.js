import nProgress from "nprogress";

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
            const editorEl = document.querySelector('#remarks .ql-editor');
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
                    remarks: content
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const statusEl = document.querySelector(`[data-questionnaire="${questionnaire_id}"] .status.pending`);
                    if (statusEl) {
                        statusEl.classList.remove('pending');
                        statusEl.classList.add('inprogress');
                    }
                } else {
                    // alert('Failed to save.');
                }
            })
            .finally(() => {
                nProgress.done();
            });
            // .catch(() => alert('Error saving remarks.'));
        }
    }
}