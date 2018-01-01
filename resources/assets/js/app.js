require('./bootstrap');

require('jquery.session/jquery.session');
require('bootstrap-3-typeahead');
var palette = require('google-palette');

// Define color palette (for charts)
// http://google.github.io/palette.js/
window.coloePalette = palette('tol', 8);

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

window.Vue = require('vue');

Vue.component('line-chart', require('./components/LineChart.vue'));
Vue.component('task-list', require('./components/TaskList.vue'));

const app = new Vue({
    el: '#app'
});
