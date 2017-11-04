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

});
