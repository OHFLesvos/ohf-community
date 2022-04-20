export default [
    {
        path: "/userprofile",
        name: "userprofile",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "userprofile" */ "@/pages/users/UserProfilePage"
                )
        }
    }
];
