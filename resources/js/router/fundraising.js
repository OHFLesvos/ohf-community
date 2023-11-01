import i18n from "@/plugins/i18n";
import ziggyRoute from "@/plugins/ziggy";

import { rememberRoute, previouslyRememberedRoute } from "@/utils/router";

import PageHeader from "@/components/layout/PageHeader.vue";
import TabNav from "@/components/layout/TabNav.vue";
import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

import { can } from "@/plugins/laravel";

const overviewNavItems = [
    {
        to: { name: "fundraising.donors.index" },
        icon: "users",
        text: i18n.t("Donors"),
        show: can("view-fundraising-entities")
    },
    {
        to: { name: "fundraising.donations.index" },
        icon: "donate",
        text: i18n.t("Donations"),
        show: can("view-fundraising-entities")
    }
];

export default [
    {
        path: "/fundraising",
        redirect: { name: "fundraising.donors.index" },
        name: 'fundraising.index'
    },
    {
        path: "/fundraising/donors",
        name: "fundraising.donors.index",
        components: {
            default: () => import("@/pages/fundraising/DonorsIndexPage.vue"),
            header: PageHeader,
            breadcrumbs: BreadcrumbsNav,
            beforeContent: TabNav
        },
        props: {
            default: route => ({ tag: route.query.tag }),
            header: {
                title: i18n.t("Overview"),
                buttons: [
                    {
                        to: { name: "fundraising.donors.create" },
                        variant: "primary",
                        icon: "plus-circle",
                        text: i18n.t("Add"),
                        show: can("manage-fundraising-entities")
                    },
                    {
                        href: ziggyRoute("api.fundraising.donors.export"),
                        icon: "download",
                        text: i18n.t("Export"),
                        show: can("view-fundraising-entities")
                    }
                ]
            },
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Donors'),
                    },
                ]
            },
            beforeContent: {
                items: overviewNavItems,
                exact: false
            }
        }
    },
    {
        path: "/fundraising/donors/create",
        name: "fundraising.donors.create",
        components: {
            default: () => import("@/pages/fundraising/DonorCreatePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            header: {
                title: i18n.t("Add donor"),
                buttons: [
                    {
                        to: { name: "fundraising.donors.index" },
                        icon: "times-circle",
                        text: i18n.t("Cancel"),
                        show: can("view-fundraising-entities")
                    }
                ]
            },
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Donors'),
                        to: { name: 'fundraising.donors.index' },
                        show: can('view-fundraising-entities')
                    },
                    {
                        text: i18n.t('Add donor'),
                    }
                ]
            }
        }
    },
    {
        path: "/fundraising/donors/:id(\\d+)",
        name: "fundraising.donors.show",
        components: {
            default: () => import("@/pages/fundraising/DonorShowPage.vue"),
            // header: PageHeader,
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            // header: route => ({
            //     title: i18n.t("Donor"),
            //     buttons: [
            //         {
            //             to: {
            //                 name: "fundraising.donors.edit",
            //                 params: { id: route.params.id }
            //             },
            //             variant: "primary",
            //             icon: "pencil-alt",
            //             text: i18n.t("Edit"),
            //             show: can("manage-fundraising-entities")
            //         },
            //         {
            //             href: ziggyRoute(
            //                 "api.fundraising.donors.donations.export",
            //                 route.params.id
            //             ),
            //             icon: "download",
            //             text: i18n.t("Export"),
            //             show: can("view-fundraising-entities")
            //         },
            //         {
            //             href: ziggyRoute(
            //                 "api.fundraising.donors.vcard",
            //                 route.params.id
            //             ),
            //             icon: "address-card",
            //             text: i18n.t("vCard"),
            //             show: can("view-fundraising-entities")
            //         },
            //     ]
            // }),
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Donors'),
                        to: previouslyRememberedRoute(
                            "fundraising.donors.show",
                            { name: "fundraising.donors.index" }
                        ),
                        show: can('view-fundraising-entities')
                    },
                    {
                        text: i18n.t('Donor'),
                    }
                ]
            }
        },
        children: [
            // {
            //     path: "",
            //     name: "fundraising.donors.show",
            //     component: () => import("@/components/fundraising/DonorDetails.vue"),
            //     props: true
            // },
            {
                path: "donations",
                name: "fundraising.donors.show.donations",
                component: () => import("@/components/fundraising/DonorDonations.vue"),
                props: true
            },
            {
                path: "budgets",
                name: "fundraising.donors.show.budgets",
                component: () => import("@/components/fundraising/DonorBudgets.vue"),
                props: true
            },
            {
                path: "comments",
                name: "fundraising.donors.show.comments",
                component: () => import("@/components/fundraising/DonorComments.vue"),
                props: true
            }
        ],
        beforeEnter: (to, from, next) => {
            rememberRoute(to, from, [
                "fundraising.donors.create",
                "fundraising.donors.edit"
            ]);
            next();
        }
    },
    {
        path: "/fundraising/donors/:id(\\d+)/edit",
        name: "fundraising.donors.edit",
        components: {
            default: () => import("@/pages/fundraising/DonorEditPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: route => ({
                items: [
                    {
                        text: i18n.t('Donors'),
                        to: { name: 'fundraising.donors.index' },
                        show: can('view-fundraising-entities')
                    },
                    {
                        to: {
                            name: "fundraising.donors.show",
                            params: { id: route.params.id }
                        },
                        text: i18n.t("Donor"),
                        show: can("view-fundraising-entities")
                    },
                    {
                        text: i18n.t('Edit donor'),
                    }
                ]
            })
        }
    },
    {
        path: "/fundraising/donations",
        name: "fundraising.donations.index",
        components: {
            default: () => import("@/pages/fundraising/DonationsIndexPage.vue"),
            header: PageHeader,
            beforeContent: TabNav,
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Overview"),
                buttons: [
                    {
                        href: ziggyRoute("api.fundraising.donations.export"),
                        icon: "download",
                        text: i18n.t("Export"),
                        show: can("view-fundraising-entities")
                    },
                    {
                        to: { name: "fundraising.donations.import" },
                        icon: "upload",
                        text: i18n.t("Import"),
                        show: can("manage-fundraising-entities")
                    }
                ]
            },
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Donations'),
                    },
                ]
            },
            beforeContent: {
                items: overviewNavItems
            }
        }
    },
    {
        path: "/fundraising/donations/:id(\\d+)/edit",
        name: "fundraising.donations.edit",
        components: {
            default: () => import("@/pages/fundraising/DonationEditPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: () => ({
                items: [
                    {
                        text: i18n.t('Donations'),
                        to: previouslyRememberedRoute(
                            "fundraising.donors.show",
                            { name: "fundraising.donations.index" }
                        ),
                        show: can("view-fundraising-entities")
                    },
                    {
                        text: i18n.t('Edit donation'),
                    }
                ]
            }),
        },
        beforeEnter: (to, from, next) => {
            rememberRoute(to, from);
            next();
        }
    },
    {
        path: "/fundraising/donations/import",
        name: "fundraising.donations.import",
        components: {
            default: () => import("@/pages/fundraising/DonationsImportPage.vue"),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Import"),
                buttons: [
                    {
                        to: { name: "fundraising.donations.index" },
                        icon: "times-circle",
                        text: i18n.t("Cancel"),
                        show: can("view-fundraising-entities")
                    }
                ]
            }
        }
    }
];
