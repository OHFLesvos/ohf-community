import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import ShopScannerPage from '@/pages/shop/ShopScannerPage'
import ShopCardManagerPage from '@/pages/shop/ShopCardManagerPage'

Vue.config.productionTip = false

new Vue({
    el: '#shop-app',
    components: {
        ShopScannerPage,
        ShopCardManagerPage
    }
});
