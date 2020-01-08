import scanQR from '@app/qr'

import Vue from 'vue'

import Icon from '@app/components/Icon'
import BankPersonCard from './components/BankPersonCard.vue'
import WithdrawalResults from './components/WithdrawalResults.vue'

Vue.component('icon', Icon);

// import BootstrapVue from 'bootstrap-vue'
// Vue.use(BootstrapVue)

new Vue({
	el: '#bank-app',
	components: {
		Icon,
		BankPersonCard,
		WithdrawalResults
	}
});

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