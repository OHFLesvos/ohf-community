import Vue from 'vue'

import ShopApp from './components/ShopApp.vue'
import ShopCardManager from './components/ShopCardManager.vue'

Vue.config.productionTip = false

new Vue({
    el: '#shop-app',
    components: {
        ShopApp,
        ShopCardManager
    }
});
