import Vue from "vue";

import VueRouter from "vue-router";
Vue.use(VueRouter);

import visitors from "@/router/visitors";
import accounting from "@/router/accounting";
import fundraising from "@/router/fundraising";
import reports from "@/router/reports";

import NotFoundPage from "@/pages/NotFoundPage";

export default new VueRouter({
    mode: "history",
    routes: [
        ...visitors,
        ...accounting,
        ...fundraising,
        ...reports,
        {
            path: "*",
            component: NotFoundPage
        }
    ]
});
