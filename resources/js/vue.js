import Vue from "vue";

// Font awesome
import "@/plugins/fontAwesome";

// Bootstrap Vue
import "@/plugins/bootstrap";

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
import DefaultApp from "@/DefaultApp.vue";
import "@/plugins/filters";

import numberFormatMixin from "@/mixins/numberFormatMixin";
import dateFormatMixin from "@/mixins/dateFormatMixin";
Vue.mixin(numberFormatMixin);
Vue.mixin(dateFormatMixin);

import laravelCanMixin from '@/mixins/laravelCanMixin'
Vue.mixin(laravelCanMixin)

import titleMixin from '@/mixins/titleMixin'
Vue.mixin(titleMixin)

if (document.getElementById("app")) {
    new Vue({
        router,
        store,
        i18n,
        render: h => h(DefaultApp)
    }).$mount("#app");
}
