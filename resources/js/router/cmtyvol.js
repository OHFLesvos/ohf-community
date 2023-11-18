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
        path: "/cmtyvol/create",
        name: "cmtyvol.create",
        components: {
            default: () => import("@/pages/cmtyvol/CommunityVolunteersCreatePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Community Volunteers'),
                        to: { name: "cmtyvol.overview" }
                    },
                    {
                        text: i18n.t('Register Community Volunteer'),
                    }
                ]
            },
        }
    },
    {
        path: "/cmtyvol/:id",
        name: "cmtyvol.show",
        components: {
            default: () => import("@/pages/cmtyvol/CommunityVolunteersShowPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Community Volunteers'),
                        to: { name: "cmtyvol.overview" }
                    },
                    {
                        text: i18n.t('Community Volunteer'),
                    }
                ]
            }
        }
    },
    {
        path: "/cmtyvol/:id/edit",
        name: "cmtyvol.edit",
        components: {
            default: () => import("@/pages/cmtyvol/CommunityVolunteersEditPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: route => ({
                items: [
                    {
                        text: i18n.t('Community Volunteers'),
                        to: { name: "cmtyvol.overview" }
                    },
                    {
                        text: i18n.t('Community Volunteer'),
                        to: {
                            name: "cmtyvol.show",
                            params: { id: route.params.id }
                        },
                    },
                    {
                        text: i18n.t('Edit community volunteer'),
                    }
                ]
            })
        }
    },
];
