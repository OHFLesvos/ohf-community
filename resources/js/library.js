import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import LibraryApp from '@/app/LibraryApp'

import store from '@/store'

import i18n from '@/plugins/i18n'
import router from '@/router/library'

import '@/plugins/vee-validate'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

Vue.config.productionTip = false

new Vue({
    el: '#library-app',
    i18n,
    router,
    store,
    components: {
        LibraryApp
    }
})
