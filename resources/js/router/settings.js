export default [
    {
        path: "/settings",
        name: "settings",
        components: {
            default: () =>
                import(
                    "@/pages/settings/SettingsPage.vue"
                )
        }
    },
];
