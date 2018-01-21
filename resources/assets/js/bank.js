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
	$('.give-cash').on('click', executeTransaction);
	$('.undo-transaction').on('click', undoTransaction);

	// Boutique
	$('.give-boutique-coupon').on('click', function(){
		var person = $(this).attr('data-person');
		var resultElem = $(this).parent();
		var name = resultElem.parents('.card').find('.card-header strong').text();
		if (confirm('Give BOUTIQUE coupon to ' + name + '?')) {
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
		if (confirm('Give DIAPERS coupon to ' + name + '?')) {
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
	$('.choose-gender').on('click', selectGender);

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
					.addClass('btn btn-primary btn-sm give-cash')
					.attr('data-person', personId)
					.attr('data-value', 1)
					.on('click', executeTransaction)
					.text(1));
			}
		}
	})
	.fail(function(jqXHR, textStatus) {
		alert(textStatus + ': ' + jqXHR.responseJSON);
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
		enableFilterSelect();
	})
	.fail(function(jqXHR, textStatus) {
		alert(textStatus);
	});
}
