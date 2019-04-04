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

//
// Barber
//
$(function(){
	$('.checkin-button').on('click', function(){
		var person_name = $(this).data('person-name');
		if (confirm(checkInConfirmationMessage + ' ' + person_name)) {
			var person_id = $(this).data('person-id');
			var btn = $(this);
			btn.children('i').removeClass('check').addClass('fa-spinner fa-spin');
			btn.removeClass('btn-primary').addClass('btn-secondary');
			btn.prop('disabled', true);

			$.post(checkinUrl, {
				"_token": csrfToken,
				"person_id": person_id
			}, function(data) {
				btn.siblings().remove();
				btn.parent().append(data.time);
				btn.remove();
				showSnackbar(data.message);
			})
			.fail(ajaxError)
			.always(function() {
				btn.removeAttr('disabled');
				btn.children('i').addClass('check').removeClass('fa-spinner fa-spin');
				btn.addClass('btn-primary').removeClass('btn-secondary');
			});	
		}
	});

	$('.delete-reservation-form').on('submit', function(e){
		var person_name = $(this).find('button[type="submit"]').data('person-name');
		if (!confirm(delereReservationConfirmMessage + ' ' + person_name)) {
			e.preventDefault();
		}
	});
});
