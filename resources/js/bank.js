import Vue from 'vue'

import FontAwesomeIcon from './components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BankSearchPage from '@/pages/bank/BankSearchPage'
import BankTransactionsPage from '@/pages/bank/BankTransactionsPage'
import BankVisitorReportPage from '@/pages/bank/BankVisitorReportPage'
import BankWithdrawalsReportPage from '@/pages/bank/BankWithdrawalsReportPage'
import RegisterPersonPage from '@/pages/people/RegisterPersonPage'
import EditPersonPage from '@/pages/people/EditPersonPage'
import ViewPersonPage from '@/pages/people/ViewPersonPage'

import { ModalPlugin } from 'bootstrap-vue'
Vue.use(ModalPlugin)

Vue.config.productionTip = false

import i18n from '@/plugins/i18n'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

new Vue({
	el: '#bank-app',
	i18n,
	components: {
		BankSearchPage,
        BankTransactionsPage,
        BankVisitorReportPage,
        BankWithdrawalsReportPage,
		RegisterPersonPage,
		EditPersonPage,
		ViewPersonPage
	}
});
