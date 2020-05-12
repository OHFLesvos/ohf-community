import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import i18n from '@/plugins/i18n'

Vue.config.productionTip = false

import UserIndexPage from '@/pages/users/UserIndexPage'

new Vue({
    el: '#user-management-app',
    i18n,
    components: {
        UserIndexPage
    }
})
