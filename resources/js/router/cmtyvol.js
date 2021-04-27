export default [
    {
        path: "/cmtyvol/overview",
        name: "cmtyvol.overview",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "cmtyvol" */ "@/pages/cmtyvol/CommunityVolunteersOverviewPage"
                )
        }
    },
    {
        path: "/reports/cmtyvol/report",
        name: "cmtyvol.report",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "cmtyvol" */ "@/pages/cmtyvol/CommunityVolunteersReportPage"
                )
        }
    }
];
