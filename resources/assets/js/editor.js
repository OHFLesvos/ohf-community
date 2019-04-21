$(document).ready(function() {

    // Define function to open filemanager window
    var lfm = function(options, cb) {
        var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
        window.SetUrl = cb;
    };

    // Define LFM summernote button
    var LFMButton = function(context) {
        var ui = $.summernote.ui;
        var button = ui.button({
            contents: '<i class="note-icon-picture"></i> ',
            tooltip: 'Insert image with filemanager',
            click: function() {
                lfm({type: 'image', prefix: '/laravel-filemanager'}, function(lfmItems, path) {
                    if (Array.isArray(lfmItems)) {
                        lfmItems.forEach(function (lfmItem) {
                            context.invoke('insertImage', lfmItem.url);
                        });
                    } else {
                        context.invoke('insertImage', lfmItems);
                    }
                });
            }
        });
        return button.render();
    };

    $('#editor').summernote({
        toolbar: [
            // [groupName, [list of button]]
            // see https://summernote.org/deep-dive/#custom-toolbar-popover
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['style', 'ul', 'ol', /*'paragraph', */]],
            ['color', ['forecolor']],
            ['insert', ['link', 'lfm', /*'picture', */ 'video', 'table']],
            ['misc', ['undo', 'redo', 'fullscreen', 'codeview']],
        ],
        styleTags: ['p', 'h3', 'h4'],
        buttons: {
            lfm: LFMButton
        },
        dialogsInBody: true
    });
});
