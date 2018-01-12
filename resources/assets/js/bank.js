pagination = require('./pagination.js');

//const QRScanner = require('qr-code-scanner');
const Instascan = require('instascan');

var delayTimer;
var lastFilterValue = "";

$(function(){
	
	filterField.on('change paste propertychange input', function(evt){
		//console.log( 'EVENT '  + evt.type );
		
		if (evt.type == 'input' || evt.type == 'propertychange') {
			clearTimeout(delayTimer);
			delayTimer = setTimeout(function(){
				//console.log('DELAY');
				applyFilter(filterField.val());
			}, 250);
		} else {
			applyFilter(filterField.val());
		}
	});

	filterField.on('keydown', function(evt){
		var isEscape = false;
		var isEnter = false;
		if ("key" in evt) {
			isEscape = (evt.key == "Escape" || evt.key == "Esc");
			isEnter = (evt.key == "Enter");
		} else {
			isEscape = (evt.keyCode == 27);
			isEnter = (evt.keyCode == 13);
		}
		if (isEscape) {
			//console.log( 'ESCAPE '  + evt.type );
			resetFilter();
		} else if (isEnter) {
			applyFilter(filterField.val());
			filterField.blur();
		}
	});
	
	filterReset.on('click', function(){
		resetFilter();
	});
	
	addFilterElem.on('click', function(){
		var filterVal = $(this).attr('data-filter');
		var prevVal = filterField.val();
		if (!prevVal.trim().split(" ").includes(filterVal.trim())) {
			filterField.focus().val( filterVal + prevVal ).change();
		} else {
			filterField.focus().val( prevVal );
		}
	});
	
	filterField.select().change();

	// Do scan QR code card and search for the number
	$('#scan-id-button').on('click', function(){
		scanQR(function(content){
			filterField.val(content).change();
		});
	});

	showStats();
});

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

function showStats() {
	$.get(todayStatsUrl, function(data){
		if (data.numberOfPersonsServed) {
			$('#stats').html('Today, we served <strong>' + data.numberOfPersonsServed + '</strong> persons, handing out <strong> ' + data.transactionValue + '</strong> drachmas.');
		} else {
			$('#stats').html('We did not yet serve any persons today.');
		}
		$('#stats').fadeIn('fast');
	});
}

function resetFilter() {
	// console.log( 'RESET filter' );
	filterField.val('').change().focus();
	resetAlert();
	resetStatus();
	$.post( resetFilterUrl, {
		"_token": csrfToken
	});
}

function applyFilter(value) {
	if (lastFilterValue == value) {
		return;
	}
	$('#stats').hide();
	//console.log('APPLY "' + value + '"');
	
	if (value != '') {
		filterReset.removeAttr('disabled');
		filterReset.addClass('bg-primary text-light');
	} else {
		filterReset.attr('disabled', 'disabled');
		filterReset.removeClass('bg-primary text-light');
	}
	
	var searchValue = value.trim();
	if (searchValue != '') {
		filterTable(searchValue, 1);
	} else {
		table.hide();
		showStats();
		resetAlert();
		resetStatus();
	}
	lastFilterValue = value;
}

function showStatus(msg) {
	statusContainer.html(msg);
}

function resetStatus() {
	statusContainer.html('');
    paginator.empty();
    paginationInfo.empty();
}

function showAlert(msg, type) {
	alertContainer.html(msg);
	alertContainer.addClass('alert alert-' + type);
	var icon = type == 'danger' ? 'warning' : 'info-circle';
	alertContainer.prepend($('<i>').addClass('fa fa-' + icon).append('&nbsp;'));
	alertContainer.show();
}

function resetAlert() {
	alertContainer.hide();
	alertContainer.removeClass();
}

function loadPage(page) {
    filterTable(filterField.val().trim(), page);
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
		table.hide();
		showStats();
		resetStatus();
		showAlert(textStatus + ": " + error, 'danger');
		console.log("Error: " + textStatus + " " + jqXHR.responseText);
	});
}

function writeRow(person) {
	var today = $('<td>');
	if (person.today > 0) {
		today.text(person.today)
			.append(' &nbsp; ')
			.append($('<a>')
				.attr('href', 'javascript:;')
				.append($('<i>')
					.addClass('fa fa-pencil')
				)
				.on('click', function(){
					var transactionInput = $('<input>')
							.attr('type', 'number')
							.attr('min', 0)
							.attr('max', transactionMaxAmount)
							.attr('value', person.today)
							.addClass('form-control form-control-sm')
							.on('focus', function(){
								$(this).select();
							})
							.on('keypress', function(e){
								if (e.keyCode == 13) { // Enter
									storeTransaction(person.id, $(this).val() - person.today);
								} 
							});
					today.empty();
					today.append(transactionInput);
					transactionInput.focus();
				})
			);                
	} else {
		today.append($('<a>')
				.attr('href', '#')
				.addClass('btn btn-secondary btn-sm')
				.text('2')
				.on('click', function(e){
					$(this).parent().html('<i class="fa fa-spinner fa-spin">');
					storeTransaction(person.id, 2);
					e.preventDefault();
				})
			);
		today.append(' &nbsp; ');
		today.append($('<a>')
				.attr('href', '#')
				.addClass('btn btn-secondary btn-sm')
				.text('1')
				.on('click', function(e){
					$(this).parent().html('<i class="fa fa-spinner fa-spin">');
					storeTransaction(person.id, 1);
					e.preventDefault();
				})
			);
	}

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

function storeTransaction(personId, value) {
	$.post( storeTransactionUrl, {
		"_token": csrfToken,
		"person_id": personId,
		"value": value
	}, function(data) {
		//alert(1);
		$('tr#person-' + personId).replaceWith(writeRow(data));
		filterField.select();
	})
	.fail(function(jqXHR, textStatus) {
		alert(textStatus + ': ' + jqXHR.responseJSON);
	});
}
