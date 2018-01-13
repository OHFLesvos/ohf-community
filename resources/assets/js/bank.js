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
			})
			.fail(function(jqXHR, textStatus) {
				var msg = jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText;
				alert('Error: ' + msg);
			});
		});
	});

	// Drachma
	$('.give-cash').on('click', function(){
		var person = $(this).attr('data-person');
		var value = $(this).attr('data-value');
		var resultElem = $(this).parent();
		storeTransaction(person, value, resultElem);
		enableFilterSelect();
	});

	// Boutique
	$('.give-boutique-coupon').on('click', function(){
		var person = $(this).attr('data-person');
		var resultElem = $(this).parent();
		var name = resultElem.parents('.card').find('.card-header strong').text();
		if (confirm('Give coupon to ' + name + '?')) {
			resultElem.html('<i class="fa fa-spinner fa-spin">');
			$.post( giveBouqiqueCouponUrl, {
				"_token": csrfToken,
				"person_id": person
			}, function(data) {
				resultElem.html(data.countdown);
				enableFilterSelect();
			})
			.fail(function(jqXHR, textStatus) {
				alert(textStatus);
			});
		}
	});

	// Diapers
	$('.give-diapers-coupon').on('click', function(){
		var person = $(this).attr('data-person');
		var resultElem = $(this).parent();
		var name = resultElem.parents('.card').find('.card-header strong').text();
		if (confirm('Give coupon to ' + name + '?')) {
			resultElem.html('<i class="fa fa-spinner fa-spin">');
			$.post( giveDiapersCouponUrl, {
				"_token": csrfToken,
				"person_id": person
			}, function(data) {
				resultElem.html(data.countdown);
				enableFilterSelect();
			})
			.fail(function(jqXHR, textStatus) {
				alert(textStatus);
			});
		}
	});

	// Gender
	$('.choose-gender').on('click', function(){
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
			enableFilterSelect();
		})
		.fail(function(jqXHR, textStatus) {
			alert(textStatus);
		});
	});

});

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
		resultElem
			.html(data.today + ' drachma ')
			.append($('<small>')
				.addClass('text-muted')
				.text('on ' + data.date));
	})
	.fail(function(jqXHR, textStatus) {
		alert(textStatus + ': ' + jqXHR.responseJSON);
	});
}
