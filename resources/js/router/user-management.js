export default [
    {
        path: "/admin",
        redirect: {
            name: "users.index"
        }
    },
    {
        path: "/admin/users",
        name: "users.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "user-management" */ "@/pages/users/UserIndexPage.vue"
                )
        }
    },
    {
        path: "/admin/users/:id(\\d+)",
        name: "users.show",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "user-management" */ "@/pages/users/UserShowPage.vue"
                )
        },
        props: {
            default: true,
        }
    },
];
