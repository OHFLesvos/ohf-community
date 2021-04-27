import "@/bootstrap";
import $ from "jquery";
import "@/vue";
import "@/editor";
import "@/navigation";
import "@/snackbar";
import "@/tagify";
import "@/shareUrl";
import "fslightbox";

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

/**
 * Method for sending post request
 */
import { postRequest } from "@/utils/form";
window.postRequest = postRequest;

/**
 * Input list with date range
 */

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

/**
 * Header mapping for import from tables
 */

$(function() {
    $(".import-form-file").change(function() {
        var table = $(".import-form-header-mapping");
        table
            .removeClass("d-none")
            .find("tbody")
            .empty()
            .append($("<tr><td>Loading...</td></tr>"));

        var data = new FormData();
        data.append("file", this.files[0]);

        $.ajax({
            url: table.data("query"),
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                var tbody = table.find("tbody").empty();
                for (var index in data.headers) {
                    var header = data.headers[index];
                    var row = $("<tr>").appendTo(tbody);
                    $("<td>")
                        .text(header)
                        .appendTo(row);
                    var td = $("<td>").appendTo(row);
                    $("<input>", {
                        type: "hidden",
                        name: "map[" + index + "][from]",
                        value: header
                    }).appendTo(td);

                    var select = $("<select>", {
                        name: "map[" + index + "][to]"
                    }).appendTo(td);
                    Object.keys(data.available).forEach(function(value) {
                        $("<option>", { value: value })
                            .text(data.available[value])
                            .appendTo(select);
                    });

                    var td2 = $("<td>").appendTo(row);
                    var checkbox = $("<input>", {
                        type: "checkbox",
                        name: "map[" + index + "][append]",
                        value: "true"
                    }).appendTo(td2);

                    if (header in data.defaults) {
                        select.val(data.defaults[header].value);
                        checkbox.prop("checked", data.defaults[header].append);
                    }
                }
            },
            error: function(xhr, status) {
                var message = status;
                try {
                    message = JSON.parse(xhr.responseText).message;
                } catch (e) {
                    // ignore
                }
                table.find("td").text("Error: " + message);
            }
        });
    });
});
