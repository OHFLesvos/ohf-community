import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import store from '@/store'

import i18n from '@/plugins/i18n'
import router from '@/router/fundraising'

import '@/plugins/vee-validate'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

Vue.config.productionTip = false

import FundraisingApp from '@/app/FundraisingApp'

new Vue({
    el: '#fundraising-app',
    store,
    router,
    i18n,
    components: {
        FundraisingApp
    }
});
