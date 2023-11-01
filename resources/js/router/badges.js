import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/badges",
        name: "badges.index",
        components: {
            default: () => import("@/pages/badges/BadgesPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Badges'),
                    }
                ]
            }
        }
    }
];
