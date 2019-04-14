$(document).ready(function() {
    $('#editor').summernote({
        toolbar: [
            // [groupName, [list of button]]
            // see https://summernote.org/deep-dive/#custom-toolbar-popover
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['forecolor']],
            ['para', ['ul', 'ol']],
            ['insert', ['link', 'picture', 'video', 'table']],
            ['misc', ['undo', 'redo', 'fullscreen']]
        ],
        dialogsInBody: true
    });
});
