import Vue from 'vue'

import FontAwesomeIcon from '@app/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import ShopScannerPage from './pages/ShopScannerPage'
import ShopCardManager from './components/ShopCardManager.vue'

Vue.config.productionTip = false

new Vue({
    el: '#shop-app',
    components: {
        ShopScannerPage,
        ShopCardManager
    }
});
