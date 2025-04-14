import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import Quill from "quill";
import 'quill/dist/quill.snow.css';

window.Alpine = Alpine;

Alpine.plugin(focus);
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
   const editor = document.querySelector('.wysiwyg');
   if (editor) {
       new Quill(editor, {
           theme: 'snow',
           placeholder: 'Add remarks here'
       });
   }
});
