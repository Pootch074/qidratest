import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import Quill from "quill";
import 'quill/dist/quill.snow.css';
import movToggle from './movToggle';
import levelToggle from './levelToggle';
import remarks from './remarks';
import recommendations from './recommendations';

window.Alpine = Alpine;
window.Quill = Quill;

Alpine.data('movToggle', movToggle);
Alpine.data('levelToggle', levelToggle);
Alpine.data('remarksEditor', remarks);
Alpine.data('recommendationsEditor', recommendations);

Alpine.plugin(focus);
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const editors = document.querySelectorAll('.wysiwyg');
    editors.forEach((editor) => {
        new Quill(editor, {
            theme: 'snow',
            placeholder: 'Add remarks here'
        });
    });
});