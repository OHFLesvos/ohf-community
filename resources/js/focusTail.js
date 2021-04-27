import $ from "jquery";

// Elements with the selector class gain focus and have their cursor set to the end
$(function() {
    $(".focus-tail").each(function() {
        var value = $(this).val();
        $(this)
            .val("")
            .focus()
            .val(value);
    });
});
