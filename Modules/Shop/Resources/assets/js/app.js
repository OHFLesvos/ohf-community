import scanQR from '../../../../../resources/js/qr'

$(function(){
	// Check shop card
	$('.check-shop-card').on('click', () => {
        scanQR((content) => {
            // TODO input validation of code
            $('#shop-container').empty().html('Searching card ...');
            document.location = shopUrl + '?code=' + content;
        });
    });
});
