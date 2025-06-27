import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
document.addEventListener('DOMContentLoaded', () => {
    const messageEditor = document.querySelector('#body');

    if (messageEditor) {

        ClassicEditor
            .create(messageEditor, {
                licenseKey: 'GPL', // Use license key for commercial applications, or 'GPL' for Open source Projects.
                toolbar: ['heading', '|', 'bold', 'italic', '|', 'numberedList', 'bulletedList', '|', 'redo', 'undo' ],
                placeholder: 'Enter your message here...'
            })
            .catch(error => {
                console.error(error);
            });
    }
});