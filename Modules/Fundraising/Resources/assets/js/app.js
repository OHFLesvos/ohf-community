import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import PhoneLink from '../../../../../resources/js/components/PhoneLink.vue';
Vue.component('phone-link', PhoneLink);

import EmailLink from '../../../../../resources/js/components/EmailLink.vue';
Vue.component('email-link', EmailLink);

import BaseTable from '../../../../../resources/js/components/BaseTable.vue'
Vue.component('base-table', BaseTable);

import DonorsTable from './components/DonorsTable.vue'
Vue.component('donors-table', DonorsTable);

new Vue({
    el: '#fundraising-app'
});