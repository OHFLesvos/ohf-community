import $ from "jquery";

$(function() {
    $(".input-list-add-button").click(function() {
        var template = $($(this).data("table")).find("tr.template");

        var index = template.data("index");
        template.data("index", index + 1);

        template
            .clone(true)
            .toggleClass("template")
            .insertBefore(template)
            .fadeIn()
            .find("[data-name]")
            .each(function() {
                $(this).attr(
                    "name",
                    $(this)
                        .data("name")
                        .replace(/\$index\$/, index)
                );
            });
    });
    $(".input-list-delete-button").click(function() {
        $(this)
            .parents("tr")
            .fadeOut(400, function() {
                $(this).remove();
            });
    });
});
