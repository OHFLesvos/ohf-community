/*
 * Instascan QR code camera 
 */
const Instascan = require('instascan');
function scanQR(callback) {
	let scanner = new Instascan.Scanner({ 
		video: document.getElementById('preview'),
		mirror: true,
		continuous: true,
	});
	Instascan.Camera.getCameras().then(function (cameras) {
	  if (cameras.length > 0) {
		scanner.addListener('scan', function (content) {
			scanner.stop();
			$('#videoPreviewModal').modal('hide');
			callback(content);
		});
		scanner.start(cameras[0]).then(function(){
			$('#videoPreviewModal').modal('show');
			$('#videoPreviewModal').on('hide.bs.modal', function (e) {
				scanner.stop();
			})
		});
	  } else {
		alert('No cameras found.');
	  }
	}).catch(function (e) {
	  console.error(e);
	});
}

var Snackbar = require('node-snackbar');
function showSnackbar(text, actionText, actionClass, callback) {
	var args = {
		text: text,
		duration: 3000,
		pos: 'bottom-center',
		actionText: actionText ? actionText : null,
		actionTextColor: null,
		customClass: actionClass ? actionClass : null, 
	};
	if (callback) {
		args['onActionClick'] = callback;
		args['duration'] = 5000;
	}
	Snackbar.show(args);
}

function ajaxError(jqXHR, textStatus) {
	var message;
	if (jqXHR.responseJSON.message) {
		if (jqXHR.responseJSON.errors) {
			message = "";
			var errors = jqXHR.responseJSON.errors;
			Object.keys(errors).forEach(function(key) {
				message += errors[key] + "\n";
			});
		} else {
			message = jqXHR.responseJSON.message;
		}
	} else {
		message = textStatus + ': ' + jqXHR.responseText;
	}
	alert(message);
}

$(function(){

	// Scan QR code card and search for the number
	$('#scan-id-button').on('click', function(){
		scanQR(function(content){
			$('#filter').val(content).parents('form').submit();
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
			$.post( registerCardUrl, {
				"_token": csrfToken,
				"person_id": person,
				"card_no": content,
			}, function(data) {
				resultElem.html('<strong>' + content.substr(0,7) + '</strong>');
				showSnackbar('QR code card has been stored');
			})
			.fail(ajaxError);
		});
	});

	// Drachma
	$('.give-cash').on('click', executeTransaction);
	$('.undo-transaction').on('click', undoTransaction);

	// Boutique
	$('.give-boutique-coupon').on('click', giveBoutiqueCoupon);
	$('.undo-boutique').on('click', resetBoutiqueCoupon);

	// Diapers
	$('.give-diapers-coupon').on('click', giveDiapersCoupon);
	$('.undo-diapers').on('click', resetDiapersCoupon);

	// Gender
	$('.choose-gender').on('click', selectGender);

	// Date of birth
	$('.choose-date-of-birth').on('click', selectDateOfBirth);
});

function executeTransaction() {
	var person = $(this).attr('data-person');
	var value = $(this).attr('data-value');
	var resultElem = $(this).parent();
	storeTransaction(person, value, resultElem);
	enableFilterSelect();
}

function undoTransaction() {
	var person = $(this).attr('data-person');
	var value = $(this).attr('data-value');
	var resultElem = $(this).parent();
	storeTransaction(person, - value, resultElem);
	enableFilterSelect();
}

function enableFilterSelect() {
	$('#filter').off('click');
	$('#filter').on('click', function(){
		$(this).select();
	});
}

function storeTransaction(personId, value, resultElem) {
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	$.post( storeTransactionUrl, {
		"_token": csrfToken,
		"person_id": personId,
		"value": value
	}, function(data) {
		if (data.today > 0) {
			resultElem.html(data.today + ' ')
				.append($('<small>')
					.addClass('text-muted')
					.attr('title', data.date)
					.text('registered ' + data.dateDiff))
				.append(' ')
				.append($('<a>')
					.attr('href', 'javascript:;')
					.addClass('undo-transaction')
					.attr('title', 'Undo')
					.attr('data-person', personId)
					.attr('data-value', value)
					.on('click', undoTransaction)
					.append($('<i>').addClass("fa fa-undo")));
			var name = resultElem.parents('.card').find('.card-header strong').text();
			showSnackbar('Transaction for ' + name + ' has been stored', 'Undo', 'warning', function(element){
				$(element).css('opacity', 0);
				storeTransaction(personId, - value, resultElem);
				enableFilterSelect();
			});
		} else {
			resultElem.empty();
			if (data.age === null || data.age >= 12) {
				resultElem.append($('<button>')
					.addClass('btn btn-primary btn-sm give-cash')
					.attr('data-person', personId)
					.attr('data-value', 2)
					.on('click', executeTransaction)
					.text(2))
					.append(' ');
			}
			if (data.age === null || data.age < 12) {
				resultElem.append($('<button>')
					.attr('type', 'button')
					.addClass('btn btn-primary btn-sm give-cash')
					.attr('data-person', personId)
					.attr('data-value', 1)
					.on('click', executeTransaction)
					.text(1));
			}
			showSnackbar('Transaction has been reverted');
		}
	})
	.fail(ajaxError);
}

function selectGender() {
	var person = $(this).attr('data-person');
	var value = $(this).attr('data-value');
	var resultElem = $(this).parent();
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	$.post( updateGenderUrl, {
		"_token": csrfToken,
		"person_id":person,
		'gender': value
	}, function(data) {
		if (value == 'm') {
			resultElem.html('<i class="fa fa-male">');
		} else if (value == 'f') {
			resultElem.html('<i class="fa fa-female">');
		}
		showSnackbar('Gender has been registered.');
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
	$.post(updateDateOfBirthUrl, {
		"_token": csrfToken,
		"person_id":person,
		'date_of_birth': dateSelect.val()
	}, function(data) {
		resultElem.html(data.date_of_birth + ' (age ' + data.age + ')');
		showSnackbar('Date of birth has been registered.');
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

function giveBoutiqueCoupon() {
	var person = $(this).attr('data-person');
	var resultElem = $(this).parent();
	//var name = resultElem.parents('.card').find('.card-header strong').text();
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	$.post( giveBoutiqueCouponUrl, {
		"_token": csrfToken,
		"person_id": person
	}, function(data) {
		resultElem.html(data.countdown)
			.append(' ')
			.append($('<a>')
				.attr('href', 'javascript:;')
				.addClass('undo-boutique')
				.attr('title', 'Undo')
				.attr('data-person', person)
				.on('click', resetBoutiqueCoupon)
				.append($('<i>').addClass("fa fa-undo")));
		showSnackbar('Boutique coupon has been registered.');
		enableFilterSelect();
	})
	.fail(ajaxError);
}

function resetBoutiqueCoupon() {
	var person = $(this).attr('data-person');
	var resultElem = $(this).parent();
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	$.post( resetBoutiqueCouponUrl, {
		"_token": csrfToken,
		"person_id": person
	}, function(data) {
		resultElem.empty()
			.append($('<button>')
				.attr('type', 'button')
				.attr('data-person', person)
				.attr('type', 'button')
				.on('click', giveBoutiqueCoupon)
				.addClass('btn btn-primary btn-sm give-boutique-coupon')
				.text('Coupon'));
		showSnackbar('Boutique coupon has been unregistered.');
		enableFilterSelect();
	})
	.fail(ajaxError);
}

function giveDiapersCoupon(){
	var person = $(this).attr('data-person');
	var resultElem = $(this).parent();
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	$.post( giveDiapersCouponUrl, {
		"_token": csrfToken,
		"person_id": person
	}, function(data) {
		resultElem.html(data.countdown)			
			.append(' ')
			.append($('<a>')
				.attr('href', 'javascript:;')
				.addClass('undo-diapers')
				.attr('title', 'Undo')
				.attr('data-person', person)
				.on('click', resetDiapersCoupon)
				.append($('<i>').addClass("fa fa-undo")));
		showSnackbar('Diapers coupon has been registered.');
		enableFilterSelect();
	})
	.fail(ajaxError);
}

function resetDiapersCoupon() {
	var person = $(this).attr('data-person');
	var resultElem = $(this).parent();
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	$.post( resetDiapersCouponUrl, {
		"_token": csrfToken,
		"person_id": person
	}, function(data) {
		resultElem.empty()
			.append($('<button>')
				.attr('type', 'button')
				.attr('data-person', person)
				.attr('type', 'button')
				.on('click', giveDiapersCoupon)
				.addClass('btn btn-primary btn-sm give-diapers-coupon')
				.text('Coupon'));
		showSnackbar('Diapers coupon has been unregistered.');
		enableFilterSelect();
	})
	.fail(ajaxError);
}
