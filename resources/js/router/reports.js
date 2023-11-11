import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/reports",
        name: "reports.index",
        components: {
            default: () => import("@/pages/reports/ReportsIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Reports'),
                    }
                ]
            }
        }
    },
    {
        path: "/reports/visitors",
        name: "reports.visitors",
        components: {
            default: () => import("@/pages/reports/VisitorReportPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Reports'),
                        to: { name: 'reports.index' }
                    },
                    {
                        text: i18n.t('Visitors'),
                    }
                ]
            }
        },
    },
    {
        path: "/reports/visitors/checkins",
        name: "reports.visitors.checkins",
        components: {
            default: () => import("@/pages/reports/VisitorCheckinsReportPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Reports'),
                        to: { name: 'reports.index' }
                    },
                    {
                        text: i18n.t('Visitor check-ins'),
                    }
                ]
            }
        },
    },
    {
        path: "/reports/fundraising/donations",
        name: "reports.fundraising.donations",
        components: {
            default: () => import("@/pages/reports/FundraisingReportPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Reports'),
                        to: { name: 'reports.index' }
                    },
                    {
                        text: i18n.t('Fundraising'),
                    }
                ]
            }
        },
    },
    {
        path: "/reports/cmtyvol/report",
        name: "reports.cmtyvol",
        components: {
            default: () => import("@/pages/cmtyvol/CommunityVolunteersReportPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Reports'),
                        to: { name: 'reports.index' }
                    },
                    {
                        text: i18n.t('Community Volunteers'),
                    }
                ]
            }
        },
    },
    {
        path: "/reports/users/permissions",
        name: "reports.users.permissions",
        components: {
            default: () => import("@/pages/reports/UserPermissionsReport.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Reports'),
                        to: { name: 'reports.index' }
                    },
                    {
                        text: i18n.t('User Permissions'),
                    }
                ]
            }
        },
    },
    {
        path: "/reports/roles/permissions",
        name: "reports.roles.permissions",
        components: {
            default: () => import("@/pages/reports/RolePermissionsReport.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Reports'),
                        to: { name: 'reports.index' }
                    },
                    {
                        text: i18n.t('Role Permissions'),
                    }
                ]
            }
        },
    },
];
