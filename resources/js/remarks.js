import nProgress from "nprogress";

export default function remarksEditor({ route, initialContent, period_id, lgu_id, questionnaire_id, user_id }) {
    return {
        quill: null,
        debounceMs: 1000,
        _debounceTimer: null,
        _inFlightController: null,
        saving: false,
        lastSavedContent: initialContent || '',

        init() {
            const waitForQuill = () => {
                const quill = window._quillEditors?.['remarks'];
                if (quill) {
                    this.quill = quill;

                    // Initialize content + saved baseline
                    quill.root.innerHTML = initialContent || '';
                    this.lastSavedContent = quill.root.innerHTML;

                    // 1) Debounced autosave after typing stops for 3s
                    quill.on('text-change', () => {
                        this._scheduleDebouncedSave();
                    });

                    // 2) Save immediately when editor loses focus
                    quill.on('selection-change', (range) => {
                        if (range == null) {
                            this._saveIfDirty();
                        }
                    });

                    // Optional: try to save when tab is hidden (only if inside allowed context)
                    document.addEventListener('visibilitychange', () => {
                        if (document.visibilityState === 'hidden' && this._isAllowedContext()) {
                            this._saveIfDirty();
                        }
                    });

                    // Warn on nav away if unsaved (only if inside allowed context)
                    window.addEventListener('beforeunload', (e) => {
                        if (this._isDirty() && this._isAllowedContext()) {
                            e.preventDefault();
                            e.returnValue = '';
                        }
                    });
                } else {
                    setTimeout(waitForQuill, 50);
                }
            };

            waitForQuill();
        },

        _isAllowedContext() {
            const container = document.querySelector('#assessment-questionnaires');
            if (!container) return false;
            const active = document.activeElement;
            return container.contains(active);
        },

        _getContent() {
            const editorEl = document.querySelector('#remarks .ql-editor');
            return editorEl ? editorEl.innerHTML.trim() : '';
        },

        _isDirty() {
            return this._getContent() !== this.lastSavedContent;
        },

        _scheduleDebouncedSave() {
            clearTimeout(this._debounceTimer);
            this._debounceTimer = setTimeout(() => {
                this._saveIfDirty();
            }, this.debounceMs);
        },

        _saveIfDirty() {
            if (!this._isAllowedContext()) return;
            if (this._isDirty()) this.save();
        },

        async save() {
            const content = this._getContent();
            if (!this._isAllowedContext()) return;

            if (this._inFlightController) {
                try { this._inFlightController.abort(); } catch (_) {}
            }
            this._inFlightController = new AbortController();

            nProgress.configure({ showSpinner: false });
            if (!this.saving) nProgress.start();
            this.saving = true;

            try {
                const res = await fetch(route, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        period_id,
                        lgu_id,
                        questionnaire_id,
                        user_id,
                        remarks: content
                    }),
                    signal: this._inFlightController.signal
                });

                let data = {};
                try { data = await res.json(); } catch (_) {}

                if (!res.ok || (data && data.success === false)) {
                    throw new Error(data?.message || `Save failed (${res.status})`);
                }

                this.lastSavedContent = content;

                const statusEl = document.querySelector(`[data-questionnaire="${questionnaire_id}"] .status.pending`);
                if (statusEl) {
                    statusEl.classList.remove('pending');
                    statusEl.classList.add('inprogress');
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Error saving remarks:', err);
                }
            } finally {
                this.saving = false;
                nProgress.done();
                this._inFlightController = null;
            }
        }
    }
}
