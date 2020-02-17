import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import BaseTable from './components/BaseTable.vue'
Vue.component('base-table', BaseTable);

import PeopleTable from './components/people/PeopleTable.vue'
Vue.component('people-table', PeopleTable);

new Vue({
    el: '#people-app'
});