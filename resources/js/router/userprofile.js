import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/userprofile",
        name: "userprofile",
        components: {
            default: () => import("@/pages/users/UserProfilePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('User Profile'),
                    }
                ]
            }
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
            default: () => import("@/pages/users/UserProfile2FAPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('User Profile'),
                        to: { name: 'userprofile' }
                    },
                    {
                        text: i18n.t('Two-Factor Authentication'),
                    }
                ]
            }
        }
    },
];
