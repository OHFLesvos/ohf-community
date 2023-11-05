import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/",
        name: "dashboard",
        components: {
            default: () => import("@/pages/dashboard/DashboardPage.vue"),
        }
    },
    {
        path: "/system-info",
        name: "system-info",
        components: {
            default: () => import("@/pages/dashboard/SystemInfoPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('System Information'),
                    }
                ]
            }
        }
    }
];
