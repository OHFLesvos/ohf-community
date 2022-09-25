import i18n from "@/plugins/i18n";
import PageHeader from "@/components/layout/PageHeader.vue";

export default [
    {
        path: "/reports",
        name: "reports.index",
        components: {
            default: () =>
                import(
                    "@/pages/reports/ReportsIndexPage.vue"
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
                    "@/pages/reports/VisitorReportPage.vue"
                )
        }
    },
    {
        path: "/reports/fundraising/donations",
        name: "reports.fundraising.donations",
        components: {
            default: () =>
                import(
                    "@/pages/reports/FundraisingReportPage.vue"
                )
        }
    }
];
