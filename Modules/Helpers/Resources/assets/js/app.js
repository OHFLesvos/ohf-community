function insertItem(val) {
    console.log(val)    
}

import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import PersonSearch from './components/PersonSearch'
Vue.component('person-search', PersonSearch)

new Vue({
    el: '#helper-app'
});
