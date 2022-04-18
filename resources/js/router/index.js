import Vue from "vue";

import VueRouter from "vue-router";
Vue.use(VueRouter);

import dashboard from "@/router/dashboard";
import visitors from "@/router/visitors";
import accounting from "@/router/accounting";
import fundraising from "@/router/fundraising";
import reports from "@/router/reports";
import userManagement from "@/router/user-management";
import cmtyvol from "@/router/cmtyvol";

import NotFoundPage from "@/pages/NotFoundPage";

export default new VueRouter({
    mode: "history",
    routes: [
        ...dashboard,
        ...visitors,
        ...accounting,
        ...fundraising,
        ...reports,
        ...userManagement,
        ...cmtyvol,
        {
            path: "*",
            component: NotFoundPage
        }
    ]
});
