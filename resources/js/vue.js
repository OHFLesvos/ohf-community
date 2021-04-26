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

// Hide prod tooltip
Vue.config.productionTip = false;

// Users app
import UserIndexPage from "@/pages/users/UserIndexPage";
if (document.getElementById("user-management-app")) {
    new Vue({
        el: "#user-management-app",
        i18n,
        components: {
            UserIndexPage
        }
    });
}
