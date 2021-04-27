export default [
    {
        path: "/visitors",
        redirect: {
            name: "visitors.listCurrent"
        }
    },
    {
        path: "/visitors/current",
        name: "visitors.listCurrent",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "visitors" */ "@/pages/visitors/CurrentVisitorsPage"
                )
        }
    }
];
