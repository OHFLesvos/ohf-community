import Vue from 'vue'

import FontAwesomeIcon from './components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BankSearchPage from './pages/bank/BankSearchPage.vue'
import BankTransactionsPage from './pages/bank/BankTransactionsPage.vue'

import { ModalPlugin } from 'bootstrap-vue'
Vue.use(ModalPlugin)

Vue.config.productionTip = false

new Vue({
	el: '#bank-app',
	components: {
		BankSearchPage,
		BankTransactionsPage
	}
});
