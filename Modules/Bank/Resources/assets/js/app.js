import { showSnackbar, handleAjaxError } from '../../../../../resources/js/utils'
import scanQR from '../../../../../resources/js/qr'

$(function(){

	// Scan QR code card and search for the number
	$('#scan-id-button').on('click', () => {
		scanQR((content) => {
			// TODO input validation of code
			$('#bank-container').empty().html('Searching card ...');
			document.location = '/bank/withdrawal/cards/' + content;
		});
	});

	// Register QR code card
	$('.register-card').on('click', () => {
		var url = $(this).data('url');
		var card = $(this).attr('data-card');
		if (card && !confirm('Do you really want to replace the card ' + card.substr(0, 7) + ' with a new one?')) {
			return;
		}
		var resultElem = $(this);
		scanQR((content) => {
			// TODO input validation of code
			axios.patch( url, {
					"card_no": content,
				})
				.then(response => {
					var data = response.data
					resultElem.html('<strong>' + content.substr(0,7) + '</strong>');
					showSnackbar(data.message);
					document.location = '/bank/withdrawal/cards/' + content;
				})
				.catch(handleAjaxError);
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
	$('#filter').on('focus', () => {
		$(this).select();
	});
}

function handoutCoupon(){
	var btn = $(this);
	var url = btn.data('url');
	var amount = btn.data('amount');
	var qrCodeEnabled = btn.data('qr-code-enabled');
	if (qrCodeEnabled) {
		scanQR((content) => {
			// TODO input validation of code
			sendHandoutRequest(btn, url, {
				"amount": amount,
				'code': content,
			});
		});
	} else {
		sendHandoutRequest(btn, url, {
			"amount": amount
		});
	}
}

function sendHandoutRequest(btn, url, postData) {
	btn.attr('disabled', 'disabled');
	axios.post(url, postData)
		.then(response => {
			var data = response.data
			btn.append(' (' + data.countdown + ')');
			btn.off('click').on('click', undoHandoutCoupon);
			showSnackbar(data.message, undoLabel, 'warning', (element) => {
				$(element).css('opacity', 0);
				btn.click();
				enableFilterSelect();
			});

			btn.removeClass('btn-primary').addClass('btn-secondary');
			enableFilterSelect();
		})
		.catch(handleAjaxError)
		.then(() => {
			btn.removeAttr('disabled');
		});	
}

function undoHandoutCoupon(){
	var btn = $(this);
	var url = btn.data('url');
	btn.attr('disabled', 'disabled');
	axios.delete(url)
		.then(resonse => {
			var data = resonse.data
			btn.html(btn.html().substring(0, btn.html().lastIndexOf(" (")));
			btn.off('click').on('click', handoutCoupon);
			showSnackbar(data.message);

			btn.removeClass('btn-secondary').addClass('btn-primary');
			enableFilterSelect();
		})
		.catch(handleAjaxError)
		.then(() => {
			btn.removeAttr('disabled');
		});	
}

function selectGender() {
	var url = $(this).data('url');
	var value = $(this).data('value');
	var resultElem = $(this).parent();
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	axios.patch( url, {
			'gender': value
		})
		.then(response => {
			var data = response.data
			if (value == 'm') {
				resultElem.html('<i class="fa fa-male">');
			} else if (value == 'f') {
				resultElem.html('<i class="fa fa-female">');
			}
			showSnackbar(data.message);
			enableFilterSelect();
		})
		.catch(handleAjaxError);
}

function selectDateOfBirth() {
	var url = $(this).data('url');
	var resultElem = $(this).parent();
	var dateSelect = $('<input>')
		.attr('type', 'text')
		.attr('max', getTodayDate())
		.attr('pattern', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
		.attr('title', 'YYYY-MM-DD')
		.attr('placeholder', 'YYYY-MM-DD')
		.addClass('form-control form-control-sm')
		.on('keydown', (evt) => {
			var isEnter = false;
			if ("key" in evt) {
				isEnter = (evt.key == "Enter");
			} else {
				isEnter = (evt.keyCode == 13);
			}
			if (isEnter && dateSelect.val().match('^[0-9]{4}-[0-9]{2}-[0-9]{2}$')) {
				storeDateOfBirth(url, dateSelect, resultElem);
			}
		});
	resultElem.empty()
		.append(dateSelect)
		.append(' ')
		.append($('<button>')
			.attr('type', 'button')
			.addClass('btn btn-primary btn-sm')
			.on('click', () => {
				if (dateSelect.val().match('^[0-9]{4}-[0-9]{2}-[0-9]{2}$')) {
					storeDateOfBirth(url, dateSelect, resultElem);
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
			.on('click', () => {
				resultElem.empty().
					append($('<button>')
						.addClass('btn btn-warning btn-sm choose-date-of-birth')
						.attr('data-url', url)
						.attr('title', 'Set date of birth')
						.on('click', selectDateOfBirth)
						.append(
							$('<i>').addClass("fa fa-calendar-plus")
						)
					);
			})
			.append(
				$('<i>').addClass("fa fa-times")
			)
		);
	dateSelect.focus();
}

function storeDateOfBirth(url, dateSelect, resultElem) {
	axios.patch(url, {
			'date_of_birth': dateSelect.val()
		})
		.then(response => {
			var data = response.data
			resultElem.html(data.date_of_birth + ' (age ' + data.age + ')');
			// Remove buttons not maching age-restrictions
			$('button[data-min_age]').each(() => {
				if ($(this).data('min_age') && data.age < $(this).data('min_age')) {
					$(this).parent().remove();
				}
			});
			$('button[data-max_age]').each(() => {
				if ($(this).data('max_age') && data.age > $(this).data('max_age')) {
					$(this).parent().remove();
				}
			});
			showSnackbar(data.message);
			enableFilterSelect();
		})
		.catch(err => {
			handleAjaxError(err);
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
	var url = $(this).data('url');
	var resultElem = $(this).parent();
	var nationalitySelect = $('<input>')
		.attr('type', 'text')
		.attr('placeholder', 'Choose nationality')
		.addClass('form-control form-control-sm')
		.on('keydown', (evt) => {
			var isEnter = false;
			if ("key" in evt) {
				isEnter = (evt.key == "Enter");
			} else {
				isEnter = (evt.keyCode == 13);
			}
			if (isEnter) {
				storeNationality(url, nationalitySelect, resultElem);
			}
		});
	resultElem.empty()
		.append(nationalitySelect)
		.append(' ')
		.append($('<button>')
			.attr('type', 'button')
			.addClass('btn btn-primary btn-sm')
			.on('click', () => {
				storeNationality(url, nationalitySelect, resultElem);
			})
			.append(
				$('<i>').addClass("fa fa-check")
			)
		)
		.append(' ')
		.append($('<button>')
			.attr('type', 'button')
			.addClass('btn btn-secondary btn-sm')
			.on('click', () => {
				resultElem.empty().
					append($('<button>')
						.addClass('btn btn-warning btn-sm choose-nationality')
						.attr('data-url', url)
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

function storeNationality(url, nationalitySelect, resultElem) {
	axios.patch(url, {
			'nationality': nationalitySelect.val()
		})
		.then(response => {
			var data = response.data
			resultElem.html(data.nationality);
			showSnackbar(data.message);
			enableFilterSelect();
		})
		.catch(err => {
			handleAjaxError(err);
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
if (typeof highlightTerms !== 'undefined') {
	$(function(){
		highlightTerms.forEach(t => highlightText(t))
	})
}