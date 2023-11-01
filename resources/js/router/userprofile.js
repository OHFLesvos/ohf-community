export default [
    {
        path: "/userprofile",
        name: "userprofile",
        components: {
            default: () => import("@/pages/users/UserProfilePage.vue")
        }
    },
    {
        path: "/userprofile/deleted",
        name: "userprofile.deleted",
        components: {
            default: () => import("@/pages/users/UserProfileDeletedPage.vue")
        }
    },
    {
        path: "/userprofile/2FA",
        name: "userprofile.2FA",
        components: {
            default: () => import("@/pages/users/UserProfile2FAPage.vue")
        }
    },
];
