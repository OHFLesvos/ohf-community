/*
 * Instascan QR code camera 
 */
const jsQR = require("jsqr");

$(document.body).append('<div class="modal" id="videoPreviewModal" tabindex="-1" role="dialog" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">' +
'<div class="modal-dialog" role="document">' +
	'<div class="modal-content">' +
		'<div class="modal-header">' +
			'<h5 class="modal-title" id="videoPreviewModalLabel">' + scannerDialogTitle + '</h5>' +
			'<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
			   ' <span aria-hidden="true">&times;</span>' +
			'</button>' +
		'</div>' +
		'<div class="modal-body">' +
		 '   <canvas id="preview" hidden style="width: 100%; height: 100%"></canvas>' +
		 '   <span id="videoPreviewMessage">' + scannerDialogWaitMessage + '...</span>' +
		'</div>' +
	'</div>' +
'</div>' +
'</div>');

var video = document.createElement("video");
var canvasElement = document.getElementById("preview");
var canvas = canvasElement.getContext("2d");
var videoPreviewMessage = $('#videoPreviewMessage');

var localStream;
var qrCallback;

function drawLine(begin, end, color) {
	canvas.beginPath();
	canvas.moveTo(begin.x, begin.y);
	canvas.lineTo(end.x, end.y);
	canvas.lineWidth = 4;
	canvas.strokeStyle = color;
	canvas.stroke();
}

function tick() {
	if (video.readyState === video.HAVE_ENOUGH_DATA) {
		videoPreviewMessage.hide();
		canvasElement.hidden = false;
		canvasElement.height = video.videoHeight;
		canvasElement.width = video.videoWidth;
		canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
		var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
		var code = jsQR(imageData.data, imageData.width, imageData.height, {
			inversionAttempts: "dontInvert",
		});
		if (code) {
			drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
			drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
			drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
			drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
			
			video.pause();
			localStream.getTracks().forEach(function(track) {
				track.stop();
			});

			$('#videoPreviewModal').modal('hide');
			qrCallback(code.data);
			return;
		}
	}
	requestAnimationFrame(tick);
}

function scanQR(callback) {
	$('#videoPreviewModal').modal('show');
	// Use facingMode: environment to attemt to get the front camera on phones
	navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
		video.srcObject = stream;
		localStream = stream;
		video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
		video.play();
		qrCallback = callback;
		requestAnimationFrame(tick);
	});
	$('#videoPreviewModal').on('hide.bs.modal', function (e) {
		video.pause();
		localStream.getTracks().forEach(function(track) {
		  track.stop();
		});
		canvas.clearRect(0, 0, canvasElement.width, canvasElement.height);
	})	
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

	// Check shop card
	$('.check-shop-card').on('click', checkShopCard);
});

function enableFilterSelect() {
	$('#filter').off('click');
	$('#filter').on('click', function(){
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
	$.post(updateDateOfBirthUrl, {
		"_token": csrfToken,
		"person_id":person,
		'date_of_birth': dateSelect.val()
	}, function(data) {
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
	$.post(updateNationalityUrl, {
		"_token": csrfToken,
		"person_id":person,
		'nationality': nationalitySelect.val()
	}, function(data) {
		resultElem.html(data.nationality);
		showSnackbar(data.message);
		enableFilterSelect();
	})
	.fail(function(jqXHR, textStatus){
		ajaxError(jqXHR, textStatus);
		nationalitySelect.select();
	});	
}

function checkShopCard() {
	scanQR(function(content){
		// TODO input validation of code
		$('#shop-container').empty().html('Searching card ...');
		document.location = shopUrl + '?code=' + content;
	});
}

// Barber shop
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
	})
});  