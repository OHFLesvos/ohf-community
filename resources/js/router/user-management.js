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
    {
        path: "/admin/roles",
        name: "roles.index",
        components: {
            default: () => import("@/pages/users/RoleIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Users'),
                        to: { name: 'users.index' }
                    },
                    {
                        text: i18n.t('Roles'),
                    }
                ]
            }
        }
    },
    {
        path: "/admin/roles/create",
        name: "roles.create",
        components: {
            default: () => import("@/pages/users/RoleCreatePage.vue"),
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
                        text: i18n.t('Roles'),
                        to: { name: 'roles.index' }
                    },
                    {
                        text: i18n.t('Create Role'),
                    }
                ]
            },
        }
    },
    {
        path: "/admin/roles/:id(\\d+)",
        name: "roles.show",
        components: {
            default: () => import("@/pages/users/RoleShowPage.vue"),
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
                        text: i18n.t('Roles'),
                        to: { name: 'roles.index' }
                    },
                    {
                        text: i18n.t('Details'),
                    }
                ]
            },
        }
    },
    {
        path: "/admin/roles/:id(\\d+)/edit",
        name: "roles.edit",
        components: {
            default: () => import("@/pages/users/RoleEditPage.vue"),
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
                        text: i18n.t('Roles'),
                        to: { name: 'roles.index' }
                    },
                    {
                        text: i18n.t('Edit Role'),
                    }
                ]
            },
        }
    },
];
