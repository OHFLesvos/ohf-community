export default [
    {
        path: "/",
        name: "dashboard",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "dashboard" */ "@/pages/dashboard/DashboardPage.vue"
                ),
        }
    }
];
