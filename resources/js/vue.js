import Vue from "vue";

// Font awesome
import "@/plugins/fontAwesome";

// Bootstrap Vue
import BootstrapVue from "bootstrap-vue";
Vue.use(BootstrapVue);

// i18n
import i18n from "@/plugins/i18n";

// ziggy routes
import ziggyMixin from "@/mixins/ziggyMixin";
Vue.mixin(ziggyMixin);

// Form validation
import "@/plugins/vee-validate";

// Hide prod tooltip
Vue.config.productionTip = false;

import router from "@/router";
import store from "@/store";
import DefaultApp from "@/DefaultApp";

if (document.getElementById("app")) {
    new Vue({
        router,
        store,
        i18n,
        render: h => h(DefaultApp)
    }).$mount("#app");
}
