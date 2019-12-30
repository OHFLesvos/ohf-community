import Vue from 'vue'

import ShopApp from './components/ShopApp.vue'
Vue.component('shop-app', ShopApp);

import ShopCardManager from './components/ShopCardManager.vue'
Vue.component('shop-card-manager', ShopCardManager);

new Vue({
    el: '#shop-app'
});
