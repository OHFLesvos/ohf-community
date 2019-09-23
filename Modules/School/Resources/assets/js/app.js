import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import SchoolClassRegisterStudent from './components/SchoolClassRegisterStudent'
Vue.component('school-class-register-student', SchoolClassRegisterStudent);

new Vue({
    el: '#school-app'
});
