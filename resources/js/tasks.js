import Vue from'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import TaskList from '@/components/collaboration/TaskList'
Vue.component('task-list', TaskList)

import i18n from '@/plugins/i18n'

Vue.config.productionTip = false

new Vue({
    el: '#tasks-app',
    i18n
});
