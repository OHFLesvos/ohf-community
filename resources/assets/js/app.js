require('./bootstrap');
require( 'jquery.session/jquery.session' );

/*
* Slideout
 */
var slideout = new Slideout({
    'panel': document.getElementById('panel'),
    'menu': document.getElementById('menu'),
    'padding': 256,
    'tolerance': 70
});

// Toggle button
$('.toggle-button').on('click', function() {
    slideout.toggle();
});

function close(eve) {
    eve.preventDefault();
    slideout.close();
}

slideout
    .on('beforeopen', function() {
        this.panel.classList.add('panel-open');
    })
    .on('open', function() {
        this.panel.addEventListener('click', close);
    })
    .on('beforeclose', function() {
        this.panel.classList.remove('panel-open');
        this.panel.removeEventListener('click', close);
    });

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
