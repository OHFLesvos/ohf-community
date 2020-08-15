import './bootstrap'
import $ from 'jquery'

/*====================================
=            ON DOM READY            =
====================================*/
$(function() {
    $('.toggle-nav').click(function() {
        toggleNav();
    });
});

/*========================================
=            CUSTOM FUNCTIONS            =
========================================*/

function showNavigation() {
    $('.site-wrapper').addClass('show-nav');
    var overlay = $('#overlay_dark');
    overlay.fadeIn('fast');
    overlay.on('click', function(){
        hideNavigation();
    });
}

function hideNavigation() {
    $('.site-wrapper').removeClass('show-nav');
    var overlay = $('#overlay_dark');
    overlay.fadeOut('fast');
    overlay.off('click');
}

function toggleNav() {
    if ($('.site-wrapper').hasClass('show-nav')) {
        // Do things on Nav Close
        hideNavigation();
    } else {
        // Do things on Nav Open
        showNavigation();
    }
}

$(function(){

    // Delete confirmation method
    $( '.delete-confirmation' ).on('click', function(){
        return confirm( $(this).attr( 'data-confirmation' ) );
    });

    //  Context navigation
    $('.context-nav-toggle').on('click', function(){
        var nav = $(this).siblings('.context-nav');
        var overlay = $('#overlay');
        if (nav.is(":visible")) {
            nav.fadeOut('fast');
            overlay.fadeOut('fast');
        } else {
            nav.fadeIn('fast');
            overlay.fadeIn('fast');
            overlay.on('click', function(){
                if ($('.context-nav').is(":visible")) {
                    nav.fadeOut('fast');
                    overlay.fadeOut('fast');
                }
            });
        }
    });

});

// Elements with the selector class gain focus and have their cursor set to the end
$(function(){
    $('.focus-tail').each(function(){
        var value = $(this).val();
        $(this).val('').focus().val(value);
    });
});

//
// Snackbar
//
import Snackbar from 'node-snackbar'

$(function(){
    $('.snack-message').each(function() {
        Snackbar.show({
            text: $(this).html(),
            duration: $(this).data('duration') ? $(this).data('duration') : 2500,
            pos: 'bottom-center',
            actionText: $(this).data('action') ? $(this).data('action') : null,
            actionTextColor: null,
            customClass: $(this).data('class'),
        });
    });
});

// Lity Lightbox
import 'lity'

/**
 * Tags input
 */
import Tagify from '@yaireo/tagify'
var tagifyAjaxController; // for aborting the call
$(document).ready(function () {
    document.querySelectorAll('input.tags').forEach((input) => {
        var suggestions = input.getAttribute('data-suggestions') != null ? JSON.parse(input.getAttribute('data-suggestions')) : [];
        var tagify = new Tagify(input, {
            whitelist: suggestions
        });

        var suggestionsUrl = input.getAttribute('data-suggestions-url');
        if (suggestionsUrl) {
            tagify.on('input', function(e){
                var value = e.detail;
                tagify.settings.whitelist.length = 0; // reset the whitelist

                // https://developer.mozilla.org/en-US/docs/Web/API/AbortController/abort
                tagifyAjaxController && tagifyAjaxController.abort();
                tagifyAjaxController = new AbortController();

                fetch(suggestionsUrl + value, {
                        signal: tagifyAjaxController.signal
                    })
                    .then(RES => RES.json())
                    .then(function(whitelist){
                        tagify.settings.whitelist = whitelist;
                        tagify.dropdown.show.call(tagify, value); // render the suggestions dropdown
                    })

            });
        }

    });
});

/**
 * Method for sending post request
 */
import { postRequest } from '@/utils/form'
window.postRequest = postRequest

//
// Share an URL
//
$(function(){
    $('[rel="share-url"]').on('click', function() {
        var url = $(this).data('url');
        if (navigator.share) {
            navigator.share({
                    title: document.title,
                    url: url,
                })
                .then(() => console.log('Successful share'))
                .catch((error) => console.log('Error sharing', error));
        } else {
            var dummy = $('<input>').val(url).appendTo('body').select();
            document.execCommand('copy');
            dummy.remove();
            Snackbar.show({
                text: 'Copied URL to clipboard.',
                duration: 2500,
                pos: 'bottom-center',
            });
        }
    });
});

/**
 * Input list with date range
 */

$(function() {
    $('.input-list-add-button').click(function() {
        var template = $($(this).data('table')).find('tr.template');

        var index = template.data('index');
        template.data('index', index + 1);

        template.clone(true)
            .toggleClass('template')
            .insertBefore(template)
            .fadeIn()
            .find('[data-name]')
                .each(function() {
                    $(this).attr('name', $(this).data('name').replace(/\$index\$/, index));
                });
    });
    $('.input-list-delete-button').click(function() {
        $(this).parents('tr')
            .fadeOut(400, function() {
            $(this).remove();
        });
    });
});


/**
 * Header mapping for import from tables
 */

$(function() {
    $('.import-form-file').change(function() {
        var table = $('.import-form-header-mapping');
        table.removeClass('d-none')
            .find('tbody')
                .empty()
                .append($('<tr><td>Loading...</td></tr>'));

        var data = new FormData();
        data.append('file', this.files[0]);

        $.ajax({
            url: table.data('query'),
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (data) {
                var tbody = table.find('tbody').empty();
                for(var index in data.headers) {
                    var header = data.headers[index];
                    var row = $('<tr>').appendTo(tbody);
                    $('<td>').text(header).appendTo(row);
                    var td = $('<td>').appendTo(row);
                    $('<input>', { 'type': 'hidden', 'name': 'map[' + index + '][from]', 'value': header }).appendTo(td);

                    var select = $('<select>', { 'name': 'map[' + index + '][to]' }).appendTo(td);
                    Object.keys(data.available).forEach(function(value) {
                        $('<option>', { 'value': value }).text(data.available[value]).appendTo(select);
                    });

                    var td2 = $('<td>').appendTo(row);
                    var checkbox = $('<input>', { 'type': 'checkbox', 'name': 'map[' + index + '][append]', 'value': 'true' }).appendTo(td2);

                    if(header in data.defaults) {
                        select.val(data.defaults[header].value);
                        checkbox.prop('checked', data.defaults[header].append);
                    }
                }
            },
            error: function (xhr, status) {
                var message = status;
                try {
                    message = JSON.parse(xhr.responseText).message;
                } catch(e) {
                    // ignore
                }
                table.find('td').text('Error: ' + message);
            },
        });
    });
});
