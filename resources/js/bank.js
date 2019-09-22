import './utils';
import './qr';

$(function(){

	// Scan QR code card and search for the number
	$('#scan-id-button').on('click', function(){
		scanQR(function(content){
			// TODO input validation of code
			$('#bank-container').empty().html('Searching card ...');
			document.location = '/bank/withdrawal/cards/' + content;
		});
	});

	// Register QR code card
	$('.register-card').on('click', function(){
		var person = $(this).attr('data-person');
		var card = $(this).attr('data-card');
		if (card && !confirm('Do you really want to replace the card ' + card.substr(0, 7) + ' with a new one?')) {
			return;
		}
		var resultElem = $(this);
		scanQR(function(content){
			// TODO input validation of code
			$.post( registerCardUrl, {
				"_token": csrfToken,
				"person_id": person,
				"card_no": content,
			}, function(data) {
				resultElem.html('<strong>' + content.substr(0,7) + '</strong>');
				showSnackbar(data.message);
				document.location = '/bank/withdrawal/cards/' + content;
			})
			.fail(ajaxError);
		});
	});

	// Coupon
	$('.give-coupon').on('click', handoutCoupon);
	
	// Gender
	$('.choose-gender').on('click', selectGender);

	// Date of birth
	$('.choose-date-of-birth').on('click', selectDateOfBirth);

	// Nationality
	$('.choose-nationality').on('click', selectNationality);

	enableFilterSelect();
});

function enableFilterSelect() {
	$('#filter').off('click');
	$('#filter').on('focus', function(){
		$(this).select();
	});
}

function handoutCoupon(){
	var btn = $(this);
	var person = btn.data('person');
	var couponType = btn.data('coupon');
	var amount = btn.data('amount');
	var qrCodeEnabled = btn.data('qr-code-enabled');
	var label = $(this).html();
	if (qrCodeEnabled) {
		scanQR(function(content){
			// TODO input validation of code
			sendHandoutRequest(btn, {
				"_token": csrfToken,
				"person_id": person,
				"coupon_type_id": couponType,
				"amount": amount,
				'code': content,
			});
		});
	} else {
		sendHandoutRequest(btn, {
			"_token": csrfToken,
			"person_id": person,
			"coupon_type_id": couponType,
			"amount": amount
		});
	}
}

function sendHandoutRequest(btn, postData) {
	btn.attr('disabled', 'disabled');
	$.post(handoutCouponUrl, postData, function(data) {
		btn.append(' (' + data.countdown + ')');
		btn.off('click').on('click', undoHandoutCoupon);
		showSnackbar(data.message, undoLabel, 'warning', function(element){
			$(element).css('opacity', 0);
			btn.click();
			enableFilterSelect();
		});

		btn.removeClass('btn-primary').addClass('btn-secondary');
		enableFilterSelect();
	})
	.fail(ajaxError)
	.always(function() {
		btn.removeAttr('disabled');
	});	
}

function undoHandoutCoupon(){
	var btn = $(this);
	var person = btn.data('person');
	var couponType = btn.data('coupon');
	var label = $(this).html();
	btn.attr('disabled', 'disabled');
	$.post(undoHandoutCouponUrl, {
		"_token": csrfToken,
		"person_id": person,
		"coupon_type_id": couponType
	}, function(data) {
		btn.html(btn.html().substring(0, btn.html().lastIndexOf(" (")));
		btn.off('click').on('click', handoutCoupon);
		showSnackbar(data.message);

		btn.removeClass('btn-secondary').addClass('btn-primary');
		enableFilterSelect();
	})
	.fail(ajaxError)
	.always(function() {
		btn.removeAttr('disabled');
	});	
}

function selectGender() {
	var person = $(this).attr('data-person');
	var value = $(this).attr('data-value');
	var resultElem = $(this).parent();
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	$.ajax( updateGenderUrl.replace(':person', person), {
			'method': 'PATCH',
			'data': {
				"_token": csrfToken,
				'gender': value
			}
		})
		.done(function(data) {
			if (value == 'm') {
				resultElem.html('<i class="fa fa-male">');
			} else if (value == 'f') {
				resultElem.html('<i class="fa fa-female">');
			}
			showSnackbar(data.message);
			enableFilterSelect();
		})
		.fail(ajaxError);
}

function selectDateOfBirth() {
	var person = $(this).attr('data-person');
	var resultElem = $(this).parent();
	var dateSelect = $('<input>')
		.attr('type', 'text')
		.attr('max', getTodayDate())
		.attr('pattern', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
		.attr('title', 'YYYY-MM-DD')
		.attr('placeholder', 'YYYY-MM-DD')
		.addClass('form-control form-control-sm')
		.on('keydown', function(evt){
			var isEnter = false;
			if ("key" in evt) {
				isEnter = (evt.key == "Enter");
			} else {
				isEnter = (evt.keyCode == 13);
			}
			if (isEnter && dateSelect.val().match('^[0-9]{4}-[0-9]{2}-[0-9]{2}$')) {
				storeDateOfBirth(person, dateSelect, resultElem);
			}
		});
	resultElem.empty()
		.append(dateSelect)
		.append(' ')
		.append($('<button>')
			.attr('type', 'button')
			.addClass('btn btn-primary btn-sm')
			.on('click', function(){
				if (dateSelect.val().match('^[0-9]{4}-[0-9]{2}-[0-9]{2}$')) {
					storeDateOfBirth(person, dateSelect, resultElem);
				} else {
					dateSelect.focus();
				}
			})
			.append(
				$('<i>').addClass("fa fa-check")
			)
		)
		.append(' ')
		.append($('<button>')
			.attr('type', 'button')
			.addClass('btn btn-secondary btn-sm')
			.on('click', function(){
				resultElem.empty().
					append($('<button>')
						.addClass('btn btn-warning btn-sm choose-date-of-birth')
						.attr('data-person', person)
						.attr('title', 'Set date of birth')
						.on('click', selectDateOfBirth)
						.append(
							$('<i>').addClass("fa fa-calendar-plus-o")
						)
					);
			})
			.append(
				$('<i>').addClass("fa fa-times")
			)
		);
	dateSelect.focus();
}

function storeDateOfBirth(person, dateSelect, resultElem) {
	$.ajax( updateDateOfBirthUrl.replace(':person', person), {
			'method': 'PATCH',
			'data': {
				"_token": csrfToken,
				'date_of_birth': dateSelect.val()
			}
		})
		.done(function(data) {
			resultElem.html(data.date_of_birth + ' (age ' + data.age + ')');
			// Remove buttons not maching age-restrictions
			$('button[data-min_age]').each(function(){
				if ($(this).data('min_age') && data.age < $(this).data('min_age')) {
					$(this).parent().remove();
				}
			});
			$('button[data-max_age]').each(function(){
				if ($(this).data('max_age') && data.age > $(this).data('max_age')) {
					$(this).parent().remove();
				}
			});
			showSnackbar(data.message);
			enableFilterSelect();
		})
		.fail(function(jqXHR, textStatus){
			ajaxError(jqXHR, textStatus);
			dateSelect.select();
		});	
}

function getTodayDate() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!

	var yyyy = today.getFullYear();
	if(dd<10){
		dd='0'+dd;
	} 
	if(mm<10){
		mm='0'+mm;
	} 
	return yyyy + '-' + mm + '-' + dd;
}

function selectNationality() {
	var person = $(this).attr('data-person');
	var resultElem = $(this).parent();
	var nationalitySelect = $('<input>')
		.attr('type', 'text')
		.attr('placeholder', 'Choose nationality')
		.addClass('form-control form-control-sm')
		.on('keydown', function(evt){
			var isEnter = false;
			if ("key" in evt) {
				isEnter = (evt.key == "Enter");
			} else {
				isEnter = (evt.keyCode == 13);
			}
			if (isEnter) {
				storeNationality(person, nationalitySelect, resultElem);
			}
		});
	resultElem.empty()
		.append(nationalitySelect)
		.append(' ')
		.append($('<button>')
			.attr('type', 'button')
			.addClass('btn btn-primary btn-sm')
			.on('click', function(){
				storeNationality(person, nationalitySelect, resultElem);
			})
			.append(
				$('<i>').addClass("fa fa-check")
			)
		)
		.append(' ')
		.append($('<button>')
			.attr('type', 'button')
			.addClass('btn btn-secondary btn-sm')
			.on('click', function(){
				resultElem.empty().
					append($('<button>')
						.addClass('btn btn-warning btn-sm choose-nationality')
						.attr('data-person', person)
						.attr('title', 'Set nationality')
						.on('click', selectNationality)
						.append(
							$('<i>').addClass("fa fa-globe")
						)
					);
			})
			.append(
				$('<i>').addClass("fa fa-times")
			)
		);
	nationalitySelect.focus();
}

function storeNationality(person, nationalitySelect, resultElem) {
	$.ajax( updateNationalityUrl.replace(':person', person), {
			'method': 'PATCH',
			'data': {
				"_token": csrfToken,
				'nationality': nationalitySelect.val()
			}	
		})
		.done(function(data) {
			resultElem.html(data.nationality);
			showSnackbar(data.message);
			enableFilterSelect();
		})
		.fail(function(jqXHR, textStatus){
			ajaxError(jqXHR, textStatus);
			nationalitySelect.select();
		});
}

// Highlighting of search results
function highlightText(text) {
	$(".mark-text").each(function(idx) {
		var innerHTML = $( this ).html();
		var index = innerHTML.toLowerCase().indexOf(text.toLowerCase());
		if (index >= 0) { 
			innerHTML = innerHTML.substring(0,index) + "<mark>" + innerHTML.substring(index,index+text.length) + "</mark>" + innerHTML.substring(index + text.length);
			$( this ).html(innerHTML);
		}
	});
}
$(function(){
	highlightTerms.forEach(t => highlightText(t))
})