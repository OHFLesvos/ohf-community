import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/visitors",
        name: "visitors.index",
        redirect: {
                name: "visitors.check_in"
        }
    },
    {
        path: "/visitors/check-in",
        name: "visitors.check_in",
        components: {
            default: () =>
                import(
                    "@/pages/visitors/VisitorCheckInPage.vue"
                ),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Visitor check-in'),
                    }
                ]
            }
        }
    },
    {
        path: "/visitors/report",
        name: "visitors.report",
        components: {
            default: () =>
                import(
                    "@/pages/reports/VisitorReportPage.vue"
                ),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Visitor check-in'),
                        to: { name: 'visitors.check_in' }
                    },
                    {
                        text: i18n.t('Reports'),
                    }
                ]
            }
        }
    },
    {
        path: "/visitors/:id/edit",
        name: "visitors.edit",
        components: {
            default: () =>
                import(
                    "@/pages/visitors/VisitorEditPage.vue"
                ),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Visitor check-in'),
                        to: { name: 'visitors.check_in' }
                    },
                    {
                        text: i18n.t('Edit visitor'),
                    }
                ]
            }
        }
    }
];
