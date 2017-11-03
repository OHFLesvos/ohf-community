require('./bootstrap');
require( 'jquery.session/jquery.session' );
require( 'chart.js' );

$(function(){

    // Delete confirmation method
    $( '.delete-confirmation' ).on('click', function(){
        return confirm( $(this).attr( 'data-confirmation' ) );
    });

});
