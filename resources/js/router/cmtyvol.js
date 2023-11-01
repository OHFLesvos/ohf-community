import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/cmtyvol/overview",
        name: "cmtyvol.overview",
        components: {
            default: () => import("@/pages/cmtyvol/CommunityVolunteersOverviewPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Community Volunteers'),
                    }
                ]
            }
        }
    },
    {
        path: "/cmtyvol/:id",
        name: "cmtyvol.show",
        components: {
            default: () => import("@/pages/cmtyvol/CommunityVolunteersShowPage.vue")
        },
        props: {
            default: true
        }
    },
];
