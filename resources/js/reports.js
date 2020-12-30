import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import i18n from '@/plugins/i18n'
import router from '@/router/reports'

import '@/plugins/vee-validate'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

Vue.config.productionTip = false

import ReportsApp from '@/app/ReportsApp'

new Vue({
    el: '#reports-app',
    // store,
    router,
    i18n,
    components: {
        ReportsApp
    }
});
