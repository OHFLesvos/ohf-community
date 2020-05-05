import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import PeopleTable from '@/components/people/PeopleTable'
import PeopleReportPage from '@/pages/people/PeopleReportPage'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

import i18n from '@/plugins/i18n'

Vue.config.productionTip = false

new Vue({
    el: '#people-app',
    i18n,
    components: {
        PeopleTable,
        PeopleReportPage
    }
});
