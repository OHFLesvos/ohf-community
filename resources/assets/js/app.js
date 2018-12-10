require('./bootstrap');

require('jquery.session/jquery.session');
var palette = require('google-palette');

// Define color palette (for charts)
// http://google.github.io/palette.js/
window.colorPalette = palette('tol', 12);

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
        var nav = $('.context-nav');
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

/**
 * hashCode method for String
 */
String.prototype.hashCode = function() {
    var hash = 0, i, chr;
    if (this.length === 0) return hash;
    for (i = 0; i < this.length; i++) {
      chr   = this.charCodeAt(i);
      hash  = ((hash << 5) - hash) + chr;
      hash |= 0; // Convert to 32bit integer
    }
    return hash;
};

/**
 * Remember open tab position of bootstrap 4 nav tabs 
 * having the class "tab-remember". The container needs to have an id.
 */
$(function(){
    $('.tab-remember').each(function(){
        var id = $(this).attr('id');
        var key = id + "Clicked";
        $(this).find('.nav-link').click(function(){
            sessionStorage.setItem(key, $(this).attr('id').replace(/-tab$/, ''));
        });
        var activeTabId;
        if (sessionStorage.getItem(key) != null) {
            activeTabId = sessionStorage.getItem(key);
        } else {
            activeTabId = $(this).siblings('.tab-content').children('.tab-pane').attr('id');
        }
        $('#'+ activeTabId + '-tab').addClass('active');
        $('#'+ activeTabId).addClass('active').addClass('show');
    });
});

// Elements with the selector class gain focus and have their cursor set to the end
$(function(){
    $('.focus-tail').each(function(){
        var value = $(this).val();
        $(this).val('').focus().val(value);
    });
});

/**
* Returns age from date of birth
*
* @param {String} dateString
* @returns {Number}
*/
function getAge(dateString) {
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}

function updateAge(elem) {
    var ageElem = $('#' + elem.attr('data-age-element'));
    if (elem.val()) {
        var age = getAge(elem.val());
        ageElem.text(age >= 0 ? age : '?');
    } else {
        ageElem.text('?');
    }
}

$(function(){
    updateAge($('input[rel="birthdate"]'));
    $('input[rel="birthdate"]').on('change paste propertychange input', function(evt){
        updateAge($(this));
	});
});

//
// Palette
//
var palette = require('google-palette');
$(function(){
    $('.colorize').each(function(){
        var i = 0;
        $(this).find('.colorize-background').each(function(){
            $(this).css('background-color', '#' + window.colorPalette[i++ % window.colorPalette.length]);
        });
        i = 0;
        $(this).find('.colorize-text').each(function(){
            $(this).css('color', '#' + colorPalette[i++ % window.colorPalette.length]);
        });
    });
});

//
// Vue
//
window.Vue = require('vue');

Vue.component('line-chart', require('./components/LineChart.vue'));
Vue.component('bar-chart', require('./components/BarChart.vue'));
Vue.component('horizontal-bar-chart', require('./components/HorizontalBarChart.vue'));
Vue.component('pie-chart', require('./components/PieChart.vue'));
Vue.component('task-list', require('./components/TaskList.vue'));

const app = new Vue({
    el: '#app'
});

//
// Autocomplete
//
require('devbridge-autocomplete')

$(function(){
    $('[rel="autocomplete"]').each(function(){
        opts = {
            showNoSuggestionNotice: true,
        };
        if ($(this).data('autocomplete-url')) {
            opts.serviceUrl = $(this).data('autocomplete-url');
            opts.dataType = 'json';
            opts.deferRequestBy = 100; //ms
        } else if ($(this).data('autocomplete-source')) {
            opts.lookup = $(this).data('autocomplete-source');
        }
        if ($(this).data('autocomplete-update')) {
            opts.onSelect = function (suggestion) {
                $($(this).data('autocomplete-update')).val(suggestion.data);
                $($(this).data('autocomplete-update')).change();
            }
            opts.onSearchError = function (suggestion) {
                $($(this).data('autocomplete-update')).val('');
                $($(this).data('autocomplete-update')).change();
            }
            opts.onSearchStart = function (suggestion) {
                $($(this).data('autocomplete-update')).val('');
            }
        }
        $(this).autocomplete(opts);
    });
});

//
// Snackbar
//
var Snackbar = require('node-snackbar')

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
var lity = require('lity');


// Post to the provided URL with the specified parameters.
window.post = function(path, parameters) {
    var form = $('<form></form>');

    form.attr("method", "post");
    form.attr("action", path);

    $.each(parameters, function(key, value) {
        var field = $('<input></input>');

        field.attr("type", "hidden");
        field.attr("name", key);
        field.attr("value", value);

        form.append(field);
    });

    // The form needs to be a part of the document in
    // order for us to be able to submit it.
    $(document.body).append(form);
    form.submit();
}
