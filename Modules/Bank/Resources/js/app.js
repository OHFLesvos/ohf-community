import { showSnackbar, handleAjaxError } from '@app/utils'
import scanQR from '@app/qr'

import Vue from 'vue'

import GenderSelector from './components/GenderSelector.vue'
import NationalitySelector from './components/NationalitySelector.vue'
import DateOfBirthSelector from './components/DateOfBirthSelector.vue'
import BankPersonCard from './components/BankPersonCard.vue'

new Vue({
	el: '#bank-app',
	components: {
		GenderSelector,
		NationalitySelector,
		DateOfBirthSelector,
		BankPersonCard
	}
});

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
	$('.register-card').on('click', function() {
		var url = $(this).data('url');
		var card = $(this).data('card');
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