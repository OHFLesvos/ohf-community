import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import DonorsIndexPage from '@/pages/fundraising/DonorsIndexPage'
import DonorCreatePage from '@/pages/fundraising/DonorCreatePage'
import DonorShowPage from '@/pages/fundraising/DonorShowPage'
import DonorEditPage from '@/pages/fundraising/DonorEditPage'
import DonationsIndexPage from '@/pages/fundraising/DonationsIndexPage'
import DonationsImportPage from '@/pages/fundraising/DonationsImportPage'
import ReportPage from '@/pages/fundraising/ReportPage'

import i18n from '@/plugins/i18n'

import './plugins/vee-validate'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

Vue.config.productionTip = false

new Vue({
    el: '#fundraising-app',
    i18n,
    components: {
        DonorsIndexPage,
        DonorCreatePage,
        DonorShowPage,
        DonorEditPage,
        DonationsIndexPage,
        DonationsImportPage,
        ReportPage
    }
});
