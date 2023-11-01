export default [
    {
        path: "/cmtyvol/overview",
        name: "cmtyvol.overview",
        components: {
            default: () =>
                import(
                    "@/pages/cmtyvol/CommunityVolunteersOverviewPage.vue"
                )
        }
    },
    {
        path: "/cmtyvol/:id",
        name: "cmtyvol.show",
        components: {
            default: () =>
                import(
                    "@/pages/cmtyvol/CommunityVolunteersShowPage.vue"
                )
        },
        props: {
            default: true
        }
    },
];
