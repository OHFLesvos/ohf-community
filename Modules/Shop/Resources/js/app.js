import Vue from 'vue'

import FontAwesomeIcon from '@app/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import ShopScannerPage from './pages/ShopScannerPage'
import ShopCardManagerPage from './pages/ShopCardManagerPage'

Vue.config.productionTip = false

new Vue({
    el: '#shop-app',
    components: {
        ShopScannerPage,
        ShopCardManagerPage
    }
});
