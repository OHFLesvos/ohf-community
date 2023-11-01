export default [
    {
        path: "/",
        name: "dashboard",
        components: {
            default: () => import("@/pages/dashboard/DashboardPage.vue"),
        }
    }
];
