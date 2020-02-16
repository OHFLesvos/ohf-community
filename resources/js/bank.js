import Vue from 'vue'

import FontAwesomeIcon from './components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BankSearchPage from './pages/bank/BankSearchPage.vue'
import BankTransactionsPage from './pages/bank/BankTransactionsPage.vue'
import RegisterPersonPage from './pages/people/RegisterPersonPage.vue'
import EditPersonPage from './pages/people/EditPersonPage.vue'
import ViewPersonPage from './pages/people/ViewPersonPage.vue'

import { ModalPlugin } from 'bootstrap-vue'
Vue.use(ModalPlugin)

Vue.config.productionTip = false

import i18n from './i18n'

new Vue({
	el: '#bank-app',
	i18n,
	components: {
		BankSearchPage,
		BankTransactionsPage,
		RegisterPersonPage,
		EditPersonPage,
		ViewPersonPage
	}
});
