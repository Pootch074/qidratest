// import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import Quill from "quill";
import 'quill/dist/quill.snow.css';
import '../css/app.css';
import '../css/custom.scss';

import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

// window.Alpine = Alpine;
window.Quill = Quill;


Alpine.plugin(focus);
// Alpine.start();

window._quillEditors = {}; // store editors keyed by ID

document.addEventListener('DOMContentLoaded', () => {
    const editors = document.querySelectorAll('.wysiwyg');
    editors.forEach((editor) => {
        const id = editor.getAttribute('id');
        if (!id) return;

        const quill = new Quill(editor, {
            theme: 'snow',
            placeholder: 'Add your notes here.',
            modules: {
                toolbar: [['bold', 'italic'], [{ 'list': 'bullet' }], ['clean']]
            }
        });

        window._quillEditors[id] = quill;
    });
});
