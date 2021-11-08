export default [
    {
        path: "/visitors",
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
                    /* webpackChunkName: "visitors" */ "@/pages/visitors/VisitorCheckInPage"
                )
        }
    },
    {
        path: "/visitors/register",
        name: "visitors.create",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "visitors" */ "@/pages/visitors/VisitorRegisterPage"
                )
        }
    }
];
