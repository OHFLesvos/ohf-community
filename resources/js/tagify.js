import Tagify from "@yaireo/tagify";
import $ from "jquery";

var tagifyAjaxController; // for aborting the call
$(function() {
    document.querySelectorAll("input.tags").forEach(input => {
        var suggestions =
            input.getAttribute("data-suggestions") != null
                ? JSON.parse(input.getAttribute("data-suggestions"))
                : [];
        var tagify = new Tagify(input, {
            whitelist: suggestions
        });

        var suggestionsUrl = input.getAttribute("data-suggestions-url");
        if (suggestionsUrl) {
            tagify.on("input", function(e) {
                var value = e.detail;
                tagify.settings.whitelist.length = 0; // reset the whitelist

                // https://developer.mozilla.org/en-US/docs/Web/API/AbortController/abort
                tagifyAjaxController && tagifyAjaxController.abort();
                tagifyAjaxController = new AbortController();

                fetch(suggestionsUrl + value, {
                    signal: tagifyAjaxController.signal
                })
                    .then(RES => RES.json())
                    .then(function(whitelist) {
                        tagify.settings.whitelist = whitelist;
                        tagify.dropdown.show.call(tagify, value); // render the suggestions dropdown
                    });
            });
        }
    });
});
