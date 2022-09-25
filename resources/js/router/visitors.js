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
                    /* webpackChunkName: "visitors" */ "@/pages/visitors/VisitorCheckInPage.vue"
                )
        }
    },
    {
        path: "/visitors/:id/edit",
        name: "visitors.edit",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "visitors" */ "@/pages/visitors/VisitorEditPage.vue"
                )
        },
        props: {
            default: true,
        }
    }
];
