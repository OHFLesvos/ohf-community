import './utils';
import './qr';

//
// Shop
//
function checkShopCard() {
	scanQR(function(content){
		// TODO input validation of code
		$('#shop-container').empty().html('Searching card ...');
		document.location = shopUrl + '?code=' + content;
	});
}
$(function(){
	// Check shop card
	$('.check-shop-card').on('click', checkShopCard);
});
