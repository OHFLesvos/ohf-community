import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import PersonSearch from '@/components/helpers/PersonSearch'
Vue.component('person-search', PersonSearch)

import HelpersReportPage from '@/pages/helpers/HelpersReportPage'
import HelpersOverviewPage from '@/pages/helpers/HelpersOverviewPage'

import i18n from '@/plugins/i18n'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

Vue.config.productionTip = false

new Vue({
    el: '#helper-app',
    i18n,
    components: {
        HelpersReportPage,
        HelpersOverviewPage
    }
});
