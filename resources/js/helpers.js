import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import PersonSearch from './components/helpers/PersonSearch'
Vue.component('person-search', PersonSearch)

import HelpersReportPage from '@/pages/helpers/HelpersReportPage'

import i18n from '@/plugins/i18n'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

new Vue({
    el: '#helper-app',
    i18n,
    components: {
        HelpersReportPage
    }
});
