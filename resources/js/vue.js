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

// Users app
if (document.getElementById("user-management-app")) {
    new Vue({
        router,
        i18n,
        render: h => h(DefaultApp)
    }).$mount("#user-management-app");
}

// Visitors app
if (document.getElementById("visitors-app")) {
    new Vue({
        router,
        i18n,
        render: h => h(DefaultApp)
    }).$mount("#visitors-app");
}

// Community volunteer app
import CommunityVolunteersReportPage from "@/pages/cmtyvol/CommunityVolunteersReportPage";
import CommunityVolunteersOverviewPage from "@/pages/cmtyvol/CommunityVolunteersOverviewPage";
import CmtyvolComments from "@/components/cmtyvol/CmtyvolComments";
if (document.getElementById("cmtyvol-app")) {
    new Vue({
        el: "#cmtyvol-app",
        i18n,
        components: {
            CommunityVolunteersReportPage,
            CommunityVolunteersOverviewPage,
            CmtyvolComments
        }
    });
}

// Accounting app
import AccountingApp from "@/app/AccountingApp";
if (document.getElementById("accounting-app")) {
    new Vue({
        router,
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

// Reports app
if (document.getElementById("reports-app")) {
    new Vue({
        router,
        i18n,
        render: h => h(DefaultApp)
    }).$mount("#reports-app");
}
