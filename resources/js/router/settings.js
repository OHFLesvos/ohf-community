import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/settings",
        name: "settings",
        components: {
            default: () => import("@/pages/settings/SettingsPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Settings'),
                    }
                ]
            }
        }
    },
];
