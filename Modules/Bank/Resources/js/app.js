import scanQR from '@app/qr'

import Vue from 'vue'

import Icon from '@app/components/Icon'
import BankPersonCard from './components/BankPersonCard.vue'

Vue.component('icon', Icon);

new Vue({
	el: '#bank-app',
	components: {
		Icon,
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

	enableFilterSelect();
});

function enableFilterSelect() {
	$('#filter').off('click');
	$('#filter').on('focus', () => {
		$(this).select();
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