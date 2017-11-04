require('./bootstrap');
require( 'jquery.session/jquery.session' );
require( 'chart.js' );

var container = $('#sidebar');
if ($.session.get('sidebar') == 1) {
    container.removeClass('d-none').addClass('d-flex');
} else {
    container.removeClass('d-flex').addClass('d-none');
}

$(function(){

    // Sidebar toggle
    $('#sidebar-toggle').on('click', function(){
        var container = $('#sidebar');
        if (container.hasClass('d-none')) {
            container.removeClass('d-none').addClass('d-flex');
            $.session.set('sidebar', 1);
        } else {
            container.removeClass('d-flex').addClass('d-none');
            $.session.set('sidebar', 0);
        }
    });

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
                    $('#overlay').fadeOut('fast');
                }
            });
        }
    });

});
