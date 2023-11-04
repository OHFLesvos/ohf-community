import i18n from "@/plugins/i18n";

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
            breadcrumbs: BreadcrumbsNav,
            beforeContent: TabNav
        },
        props: {
            default: route => ({ tag: route.query.tag }),
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
        components: {
            default: () => import("@/pages/fundraising/DonorShowPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
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
            {
                path: "",
                name: "fundraising.donors.show",
                component: () => import("@/components/fundraising/DonorDetails.vue"),
                props: true
            },
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
            beforeContent: TabNav,
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
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
