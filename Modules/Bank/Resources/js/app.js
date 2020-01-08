import Vue from 'vue'

import Icon from '@app/components/Icon'
import BankPersonCard from './components/BankPersonCard.vue'
import WithdrawalResults from './components/WithdrawalResults.vue'

Vue.component('icon', Icon);

new Vue({
	el: '#bank-app',
	components: {
		Icon,
		BankPersonCard,
		WithdrawalResults
	}
});
