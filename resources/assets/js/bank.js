/*
 * Instascan QR code camera 
 */
const Instascan = require('instascan');
function scanQR(callback) {
	let scanner = new Instascan.Scanner({ 
		video: document.getElementById('preview'),
		mirror: false,
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
	
	// // Do scan QR code card and search for the number
	$('#scan-id-button').on('click', function(){
		scanQR(function(content){
			$('#filter').val(content).parents('form').submit();
		});
	});

	// Drachma
	$('.give-cash').on('click', function(){
		var person = $(this).attr('data-person');
		var value = $(this).attr('data-value');
		var resultElem = $(this).parent();
		storeTransaction(person, value, resultElem);
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
		})
		.fail(function(jqXHR, textStatus) {
			alert(textStatus);
		});
	});

});

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

function writeRow(person) {
	// Card
	var card;
	if (person.card_no) {
		var refresh = $('<i>')
			.addClass('fa')
			.addClass('fa-refresh');
		var msg = 'Really revoke the card ' + person.card_no.substr(0, 7) + ' and issue a new one?';
		card = $('<span>')
			.text(person.card_no.substr(0, 7) + ' ')
			.append(createCardLink(person.id, refresh, msg));
	} else {
		card = createCardLink(person.id, 'Give card');
	}
}

function createCardLink(person_id, caption, confirmMessage) {
	return $('<a>')
			.attr('href', 'javascript:;')
			.html(caption)
			.on('click', function(){
				if (!confirmMessage || confirm(confirmMessage)) {
					scanQR(function(content){
						$.post( registerCardUrl, {
							"_token": csrfToken,
							"person_id": person_id,
							"card_no": content,
						}, function(data) {
							$('tr#person-' + person_id).replaceWith(writeRow(data));
							filterField.select();
						})
						.fail(function(jqXHR, textStatus) {
							var msg = jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText;
							alert('Error: ' + msg);
						});
					});
				}
			});
}

