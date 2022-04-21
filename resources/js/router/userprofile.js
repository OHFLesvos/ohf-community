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
    },
    {
        path: "/userprofile/deleted",
        name: "userprofile.deleted",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "userprofile" */ "@/pages/users/UserProfileDeletedPage"
                )
        }
    },
    {
        path: "/userprofile/2FA",
        name: "userprofile.2FA",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "userprofile" */ "@/pages/users/UserProfile2FAPage"
                )
        }
    },
];
