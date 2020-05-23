import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import DonorsIndexPage from '@/pages/fundraising/DonorsIndexPage'
import DonorShowPage from '@/pages/fundraising/DonorShowPage'
import DonationsIndexPage from '@/pages/fundraising/DonationsIndexPage'
import DonorsReport from '@/components/fundraising/DonorsReport'

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
        DonorShowPage,
        DonationsIndexPage,
        DonorsReport
    }
});
