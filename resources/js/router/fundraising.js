import i18n from "@/plugins/i18n";

import { rememberRoute, previouslyRememberedRoute } from "@/utils/router";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

import { can } from "@/plugins/laravel";

export default [
    {
        path: "/fundraising",
        name: 'fundraising.index',
        components: {
            default: () => import("@/pages/fundraising/FundraisingIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: route => ({ tag: route.query.tag }),
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Donation Management'),
                    },
                ]
            },
        }
    },
    {
        path: "/fundraising/donors",
        name: "fundraising.donors.index",
        components: {
            default: () => import("@/pages/fundraising/DonorsIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: route => ({ tag: route.query.tag }),
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Donation Management'),
                        to: { name: 'fundraising.index' }
                    },
                    {
                        text: i18n.t('Donors'),
                    },
                ]
            },
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
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Donation Management'),
                        to: { name: 'fundraising.index' }
                    },
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
                        text: i18n.t('Donation Management'),
                        to: { name: 'fundraising.index' }
                    },
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
                        text: i18n.t('Donation Management'),
                        to: { name: 'fundraising.index' }
                    },
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
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Donation Management'),
                        to: { name: 'fundraising.index' }
                    },
                    {
                        text: i18n.t('Donations'),
                    },
                ]
            },
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
                        text: i18n.t('Donation Management'),
                        to: { name: 'fundraising.index' }
                    },
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
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: () => ({
                items: [
                    {
                        text: i18n.t('Donation Management'),
                        to: { name: 'fundraising.index' }
                    },
                    {
                        text: i18n.t('Import'),
                    }
                ]
            }),
        }
    },
    {
        path: "/fundraising/export",
        name: "fundraising.export",
        components: {
            default: () => import("@/pages/fundraising/FundraisingExportPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: () => ({
                items: [
                    {
                        text: i18n.t('Donation Management'),
                        to: { name: 'fundraising.index' }
                    },
                    {
                        text: i18n.t('Export'),
                    }
                ]
            }),
        }
    }
];
