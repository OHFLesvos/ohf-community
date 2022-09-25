export default [
    {
        path: "/badges",
        name: "badges.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "badges" */ "@/pages/badges/BadgesPage.vue"
                ),
        }
    }
];
