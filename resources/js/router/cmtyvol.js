import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/cmtyvol",
        name: "cmtyvol.index",
        components: {
            default: () => import("@/pages/cmtyvol/CommunityVolunteersIndexPage.vue"),
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
        path: "/cmtyvol/responsibilities",
        name: "cmtyvol.responsibilities.index",
        components: {
            default: () => import("@/pages/cmtyvol/responsibilities/ResponsibilitiesIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Community Volunteers'),
                        to: { name: "cmtyvol.index" }
                    },
                    {
                        text: i18n.t('Responsibilities'),
                    }
                ]
            },
        }
    },
    {
        path: "/cmtyvol/responsibilities/_create",
        name: "cmtyvol.responsibilities.create",
        components: {
            default: () => import("@/pages/cmtyvol/responsibilities/ResponsibilitiesCreatePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Community Volunteers'),
                        to: { name: "cmtyvol.index" }
                    },
                    {
                        text: i18n.t('Responsibilities'),
                        to: { name: "cmtyvol.responsibilities.index" }
                    },
                    {
                        text: i18n.t('Add responsibility'),
                    }
                ]
            },
        }
    },
    {
        path: "/cmtyvol/responsibilities/:id/edit",
        name: "cmtyvol.responsibilities.edit",
        components: {
            default: () => import("@/pages/cmtyvol/responsibilities/ResponsibilitiesEditPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Community Volunteers'),
                        to: { name: "cmtyvol.index" }
                    },
                    {
                        text: i18n.t('Responsibilities'),
                        to: { name: "cmtyvol.responsibilities.index" }
                    },
                    {
                        text: i18n.t('Edit responsibility'),
                    }
                ]
            },
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
                        to: { name: "cmtyvol.index" }
                    },
                    {
                        text: i18n.t('Register Community Volunteer'),
                    }
                ]
            },
        }
    },
    {
        path: "/cmtyvol/:id(\\d+)",
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
                        to: { name: "cmtyvol.index" }
                    },
                    {
                        text: i18n.t('Community Volunteer'),
                    }
                ]
            }
        }
    },
    {
        path: "/cmtyvol/:id(\\d+)/edit",
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
                        to: { name: "cmtyvol.index" }
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
