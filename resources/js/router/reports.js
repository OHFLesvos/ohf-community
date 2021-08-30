import i18n from "@/plugins/i18n";
import PageHeader from "@/components/layout/PageHeader";

export default [
    {
        path: "/reports",
        name: "reports.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "reports" */ "@/pages/reports/ReportsIndexPage"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Reports"),
                container: true
            }
        }
    },
    {
        path: "/reports/visitors/checkins",
        name: "reports.visitors.checkins",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "reports" */ "@/pages/reports/VisitorReportPage"
                )
        }
    },
    {
        path: "/reports/fundraising/donations",
        name: "reports.fundraising.donations",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "reports" */ "@/pages/reports/FundraisingReportPage"
                )
        }
    }
];
