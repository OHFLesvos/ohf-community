import Snackbar from "node-snackbar";
import $ from "jquery";

$(function() {
    $('[rel="share-url"]').on("click", function() {
        var url = $(this).data("url");
        if (navigator.share) {
            navigator
                .share({
                    title: document.title,
                    url: url
                })
                .then(() => console.log("Successful share"))
                .catch(error => console.log("Error sharing", error));
        } else {
            var dummy = $("<input>")
                .val(url)
                .appendTo("body")
                .select();
            document.execCommand("copy");
            dummy.remove();
            Snackbar.show({
                text: "Copied URL to clipboard.",
                duration: 2500,
                pos: "bottom-center"
            });
        }
    });
});
