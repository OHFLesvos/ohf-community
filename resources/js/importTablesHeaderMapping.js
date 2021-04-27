import $ from "jquery";

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
