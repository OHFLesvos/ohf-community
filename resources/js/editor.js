/* Import TinyMCE */
import tinymce from "tinymce";

/* Default icons are required for TinyMCE 5.3 or above */
import "tinymce/icons/default";

/* A theme is also required */
import "tinymce/themes/silver";

/* Import the skin */
import "tinymce/skins/ui/oxide/skin.css";

/* Import plugins */
import "tinymce/plugins/advlist";
import "tinymce/plugins/code";
import "tinymce/plugins/emoticons";
import "tinymce/plugins/emoticons/js/emojis";
import "tinymce/plugins/link";
import "tinymce/plugins/lists";
import "tinymce/plugins/table";
import "tinymce/plugins/image";
import "tinymce/plugins/media";
import "tinymce/plugins/autoresize";

/* Initialize TinyMCE */
var initEditor = function() {
    if (document.getElementById("editor")) {
        var editor_config = {
            selector: "textarea#editor",
            menubar: false,
            path_absolute: "/",
            object_resizing: true,
            plugins:
                "advlist code emoticons link lists table image media autoresize",
            toolbar:
                "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | table | link emoticons image media | undo redo | code",
            skin: false,
            content_css: false,
            content_style: "* { font-family: sans-serif; }",
            file_picker_callback: function(callback, value, meta) {
                var x =
                    window.innerWidth ||
                    document.documentElement.clientWidth ||
                    document.getElementsByTagName("body")[0].clientWidth;
                var y =
                    window.innerHeight ||
                    document.documentElement.clientHeight ||
                    document.getElementsByTagName("body")[0].clientHeight;

                var cmsURL =
                    editor_config.path_absolute +
                    "laravel-filemanager?editor=" +
                    meta.fieldname;
                if (meta.filetype == "image") {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: "Filemanager",
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };
        tinymce.init(editor_config);
    }
};

if (
    document.readyState === "complete" ||
    (document.readyState !== "loading" && !document.documentElement.doScroll)
) {
    initEditor();
} else {
    document.addEventListener("DOMContentLoaded", initEditor);
}
