import Snackbar from "node-snackbar";
import $ from "jquery";

$(function() {
    $(".snack-message").each(function() {
        Snackbar.show({
            text: $(this).html(),
            duration: $(this).data("duration")
                ? $(this).data("duration")
                : 2500,
            pos: "bottom-center",
            actionText: $(this).data("action") ? $(this).data("action") : null,
            actionTextColor: null,
            customClass: $(this).data("class")
        });
    });
});
