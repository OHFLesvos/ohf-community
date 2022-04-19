export default [
    {
        path: "/badges/vue",
        name: "badges.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "badges" */ "@/pages/badges/BadgesPage"
                ),
        }
    }
];
