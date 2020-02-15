import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import ShopScannerPage from '@/pages/shop/ShopScannerPage'
import ShopCardManagerPage from '@/pages/shop/ShopCardManagerPage'

Vue.config.productionTip = false

import i18n from './i18n'

new Vue({
    el: '#shop-app',
    i18n,
    components: {
        ShopScannerPage,
        ShopCardManagerPage
    }
});
