require('./bootstrap');

require('jquery.session/jquery.session');
require('bootstrap-3-typeahead');
var palette = require('google-palette');

// Define color palette (for charts)
// http://google.github.io/palette.js/
window.coloePalette = palette('tol', 8);

window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue'));
const app = new Vue({
    el: '#app'
});

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
