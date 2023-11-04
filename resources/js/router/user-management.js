import i18n from "@/plugins/i18n";

import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

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
            default: () => import("@/pages/users/UserIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Users'),
                    }
                ]
            }
        }
    },
    {
        path: "/admin/users/create",
        name: "users.create",
        components: {
            default: () => import("@/pages/users/UserCreatePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Users'),
                        to: { name: 'users.index' }
                    },
                    {
                        text: i18n.t('Create User'),
                    }
                ]
            },
        }
    },
    {
        path: "/admin/users/:id(\\d+)",
        name: "users.show",
        components: {
            default: () => import("@/pages/users/UserShowPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Users'),
                        to: { name: 'users.index' }
                    },
                    {
                        text: i18n.t('Details'),
                    }
                ]
            },
        }
    },
    {
        path: "/admin/users/:id(\\d+)/edit",
        name: "users.edit",
        components: {
            default: () => import("@/pages/users/UserEditPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Users'),
                        to: { name: 'users.index' }
                    },
                    {
                        text: i18n.t('Edit User'),
                    }
                ]
            },
        }
    },
];
