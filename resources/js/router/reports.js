export default [
    {
        path: "/reports",
        redirect: {
            name: "visitors.listCurrent"
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
