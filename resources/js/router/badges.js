export default [
    {
        path: "/badges",
        name: "badges.index",
        components: {
            default: () =>
                import(
                    "@/pages/badges/BadgesPage.vue"
                ),
        }
    }
];
