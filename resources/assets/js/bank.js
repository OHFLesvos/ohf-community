/*
 * Instascan QR code camera 
 */
/*
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
*/

var delayTimer;
var lastFilterValue = "";

$(function(){
	
	// Do scan QR code card and search for the number
	$('#scan-id-button').on('click', function(){
		scanQR(function(content){
			filterField.val(content).change();
		});
	});

	$('.give-cash').on('click', function(){
		var person = $(this).attr('data-person');
		var value = $(this).attr('data-value');
		var resultElem = $(this).parent();
		storeTransaction(person, value, resultElem);
	});

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

function filterTable(filter, page) {
    paginator.empty();
    paginationInfo.empty();
	showStatus('Searching...'); //  for \'' + filter + '\'
	$.post( filterUrl, {
		"_token": csrfToken,
		"filter": filter,
        "page": page
	}, function(data) {
		var tbody = table.children('tbody');
		if (data.results.length > 0) {
			tbody.empty();
			$.each(data.results, function(k, v){
				tbody.append(writeRow(v));
			});
			table.show();
			resetAlert();
            resetStatus();
			//showStatus(data.results.length < data.total ? 'Showing <strong>' + data.results.length + '</strong> of <strong>' + data.total + '</strong> persons, refine your search.' : 'Found <strong>' + data.results.length + '</strong> persons.');
            pagination.updatePagination(paginator, data, loadPage);
            paginationInfo.html( data.from + ' - ' + data.to + ' of ' + data.total );
		} else {
			table.hide();
			showStats();
			resetStatus();
			var msg = $('<span>').text('No results. ')
				.append($('<a>')
					.attr('href', createNewRecordUrl + (data.register ? '?' + data.register : ''))
					.append('Register new person'));
			showAlert(msg, 'info');
		}
	})
	.fail(function(jqXHR, textStatus, error) {
		var msg = jqXHR.responseJSON.message ? jqXHR.responseJSON.message : textStatus + ": " + error;
		table.hide();
		showStats();
		resetStatus();
		showAlert(msg, 'danger');
		console.log("Error: " + textStatus + " " + jqXHR.responseText);
	});
}

function writeRow(person) {
	var today = $('<td>');

	// Gender icon
	var genderIcon;
    if (person.gender == 'f') {
		genderIcon = createIcon('female');
    } else if (person.gender == 'm') {
		genderIcon = createIcon('male');
	} else {
		genderIcon = $('<a>').attr('href', '#')
			.append(createIcon('question-circle-o'))
			.on('click', function(){
				$(this).parent()
					.empty()
					.addClass('text-nowrap')
					.append(createChooseGenderIcon(person, 'male', 'm'))
					.append('&nbsp; ')
					.append(createChooseGenderIcon(person, 'female', 'f'));
			});
	}

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

	return $('<tr>')
		.attr('id', 'person-' + person.id)
		.addClass(person.today > 0 ? 'table-success' : null)
		.append($('<td>')
			.addClass('text-center')
			.append(genderIcon))
		.append($('<td>')
			.append($('<a>')
				.attr('href', '../people/' + person.id)
				.text(person.family_name)
			)
		)
		.append($('<td>')
			.append($('<a>')
				.attr('href', '../people/' + person.id)
				.text(person.name)
			)
		)
		.append($('<td>').text(person.age))
		.append($('<td>').append(card))
		.append($('<td>').text(person.police_no))
		.append($('<td>').text(person.case_no))
		.append($('<td>').text(person.medical_no))
		.append($('<td>').text(person.registration_no))
		.append($('<td>').text(person.section_card_no))
		.append($('<td>').text(person.temp_no))
		.append($('<td>').text(person.nationality))
		.append($('<td>').text(person.remarks))
		// Boutique coupon
		.append($('<td>').html(function(){ 
			if (person.boutique_coupon) {
				return person.boutique_coupon;
			}
			return $('<a>')
				.attr('href', 'javascript:;')
				.text('Give coupon')
				.on('click', function(){
					if (confirm('Give coupon to ' + person.family_name + ' ' + person.name + '?')) {
						$.post( giveBouqiqueCouponUrl, {
							"_token": csrfToken,
							"person_id": person.id
						}, function(data) {
							$('tr#person-' + person.id).replaceWith(writeRow(data));
							filterField.select();
						})
						.fail(function(jqXHR, textStatus) {
							alert(textStatus);
						});
					}
				});
		}))
		// Diapers coupon
		.append($('<td>').html(function(){ 
			if (person.diapers_coupon) {
				return person.diapers_coupon;
			}
			return $('<a>')
				.attr('href', 'javascript:;')
				.text('Give coupon')
				.on('click', function(){
					if (confirm('Give coupon to ' + person.family_name + ' ' + person.name + '?')) {
						$.post( giveDiapersCouponUrl, {
							"_token": csrfToken,
							"person_id": person.id
						}, function(data) {
							$('tr#person-' + person.id).replaceWith(writeRow(data));
							filterField.select();
						})
						.fail(function(jqXHR, textStatus) {
							alert(textStatus);
						});
					}
				});
		}))
		.append(today);
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

function createIcon(icon) {
	return $('<i>')
		.addClass('fa')
		.addClass('fa-' + icon);
}

function createChooseGenderIcon(person, icon, value) {
	return $('<a>').attr('href', '#')
		.append(createIcon(icon))
		.on('click', function(){
			$.post( updateGenderUrl, {
				"_token": csrfToken,
				"person_id": person.id,
				'gender': value
			}, function(data) {
				$('tr#person-' + person.id).replaceWith(writeRow(data));
				filterField.select();
			})
			.fail(function(jqXHR, textStatus) {
				alert(textStatus);
			});
		});
}
