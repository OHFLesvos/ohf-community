import Vue from 'vue'

import FontAwesomeIcon from '@app/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon);

import WithdrawalResults from './components/WithdrawalResults.vue'
import WithdrawalTransactions from './components/WithdrawalTransactions.vue'

Vue.config.productionTip = false

new Vue({
	el: '#bank-app',
	components: {
		FontAwesomeIcon,
		WithdrawalResults,
		WithdrawalTransactions
	}
});
