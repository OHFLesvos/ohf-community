import Vue from "vue";

// Font awesome
import FontAwesomeIcon from "@/components/common/FontAwesomeIcon";
Vue.component("font-awesome-icon", FontAwesomeIcon);

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

// Accounting app
import AccountingApp from "@/app/AccountingApp";
if (document.getElementById("accounting-app")) {
    new Vue({
        router,
        store,
        i18n,
        render: h => h(AccountingApp)
    }).$mount("#accounting-app");
}

// Fundraising app
import FundraisingApp from "@/app/FundraisingApp";
if (document.getElementById("fundraising-app")) {
    new Vue({
        store,
        router,
        i18n,
        render: h => h(FundraisingApp)
    }).$mount("#fundraising-app");
}
