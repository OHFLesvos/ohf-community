import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import PeopleTable from '@/components/people/PeopleTable'

import i18n from '@/plugins/i18n'

Vue.config.productionTip = false

new Vue({
    el: '#people-app',
    i18n,
    components: {
        PeopleTable
    }
});
