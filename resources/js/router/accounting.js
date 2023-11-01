import i18n from "@/plugins/i18n";
import ziggyRoute from "@/plugins/ziggy";

import { can } from "@/plugins/laravel";

import PageHeader from "@/components/layout/PageHeader.vue";
import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";

export default [
    {
        path: "/accounting",
        name: "accounting.index",
        components: {
            default: () => import("@/pages/accounting/AccountingIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/wallets",
        name: "accounting.wallets.index",
        components: {
            default: () => import("@/pages/accounting/WalletsIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t('Manage wallets'),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/wallets/create",
        name: "accounting.wallets.create",
        components: {
            default: () => import("@/pages/accounting/WalletCreatePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t('Manage wallets'),
                        to: { name: "accounting.wallets.index" }
                    },
                    {
                        text: i18n.t("Create wallet"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/wallets/:id/edit",
        name: "accounting.wallets.edit",
        components: {
            default: () => import("@/pages/accounting/WalletEditPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t('Manage wallets'),
                        to: { name: "accounting.wallets.index" }
                    },
                    {
                        text: i18n.t("Edit wallet"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/categories",
        name: "accounting.categories.index",
        components: {
            default: () => import("@/pages/accounting/CategoryIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Categories"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/categories/create",
        name: "accounting.categories.create",
        components: {
            default: () => import("@/pages/accounting/CategoryCreatePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Categories"),
                        to: { name: 'accounting.categories.index' },
                    },
                    {
                        text: i18n.t("Create category"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/categories/:id",
        name: "accounting.categories.show",
        components: {
            default: () => import("@/pages/accounting/CategoryViewPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Categories"),
                        to: { name: 'accounting.categories.index' },
                    },
                    {
                        text: i18n.t("Details"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/categories/:id/edit",
        name: "accounting.categories.edit",
        components: {
            default: () => import("@/pages/accounting/CategoryEditPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Categories"),
                        to: { name: 'accounting.categories.index' },
                    },
                    {
                        text: i18n.t("Edit category"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/projects",
        name: "accounting.projects.index",
        components: {
            default: () => import("@/pages/accounting/ProjectIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Projects"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/projects/create",
        name: "accounting.projects.create",
        components: {
            default: () => import("@/pages/accounting/ProjectCreatePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Projects"),
                        to: { name: 'accounting.projects.index' },
                    },
                    {
                        text: i18n.t("Create project"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/projects/:id/edit",
        name: "accounting.projects.edit",
        components: {
            default: () => import("@/pages/accounting/ProjectEditPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: {
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Projects"),
                        to: { name: 'accounting.projects.index' },
                    },
                    {
                        text: i18n.t("Edit project"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/suppliers",
        name: "accounting.suppliers.index",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/SuppliersIndexPage.vue"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Suppliers"),
                buttons: [
                    {
                        to: { name: "accounting.suppliers.create" },
                        variant: "primary",
                        icon: "plus-circle",
                        text: i18n.t("Add"),
                        show: can("manage-suppliers")
                    },
                    {
                        to: { name: "accounting.index" },
                        icon: "money-bill-alt",
                        text: i18n.t("Accounting"),
                        show:
                            can("view-accounting-summary") ||
                            can("view-transactions")
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/suppliers/create",
        name: "accounting.suppliers.create",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/SupplierCreatePage.vue"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Register supplier")
            }
        }
    },
    {
        path: "/accounting/suppliers/:id",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/SupplierViewPage.vue"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: route => ({
                title: i18n.t("Supplier"),
                buttons: [
                    {
                        to: { name: "accounting.suppliers.index" },
                        variant: "secondary",
                        icon: "arrow-left",
                        text: i18n.t("Overview"),
                        show: true
                    },
                    {
                        to: {
                            name: "accounting.suppliers.edit",
                            params: { id: route.params.id }
                        },
                        variant: "primary",
                        icon: "edit",
                        text: i18n.t("Edit"),
                        show: can("manage-suppliers")
                    }
                ]
            })
        },
        children: [
            {
                path: "",
                name: "accounting.suppliers.show",
                component: () =>
                    import(
                        "@/components/accounting/SupplierDetails.vue"
                    ),
                props: true
            },
            {
                path: "transactions",
                name: "accounting.suppliers.show.transactions",
                component: () =>
                    import(
                        "@/components/accounting/SupplierTransactions.vue"
                    ),
                props: true
            }
        ]
    },
    {
        path: "/accounting/suppliers/:id/edit",
        name: "accounting.suppliers.edit",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/SupplierEditPage.vue"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Edit supplier")
            }
        }
    },
    {
        path: "/accounting/transactions/summary",
        name: "accounting.transactions.summary",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/SummaryPage.vue"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Summary"),
                buttons: [
                    {
                        to: { name: "accounting.index" },
                        icon: "home",
                        text: i18n.t("Overview")
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions/history",
        name: "accounting.transactions.history",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/TransactionsHistoryPage.vue"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("History"),
                buttons: [
                    {
                        to: { name: "accounting.index" },
                        icon: "home",
                        text: i18n.t("Overview")
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions/controlling",
        name: "accounting.transactions.controlling",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/TransactionsControllingPage.vue"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Controlling"),
                buttons: [
                    {
                        to: { name: "accounting.index" },
                        icon: "home",
                        text: i18n.t("Overview")
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions",
        name: "accounting.transactions.index",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/TransactionsIndexPage.vue"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: route => ({
                title: i18n.t("Transactions"),
                buttons: [
                    {
                        to: {
                            name: "accounting.transactions.create",
                            query: { wallet: route.query.wallet ?? null }
                        },
                        icon: "plus-circle",
                        text: i18n.t("Add"),
                        variant: "primary",
                        show: can("create-transactions")
                    },
                    {
                        to: {
                            name: "accounting.transactions.summary",
                            query: { wallet: route.query.wallet ?? null }
                        },
                        icon: "calculator",
                        text: i18n.t("Summary"),
                        show: can("view-accounting-summary")
                    },
                    route.query.wallet ? {
                        href: ziggyRoute(
                            "accounting.webling.index",
                            route.query.wallet
                        ),
                        icon: "cloud-upload-alt",
                        text: i18n.t("Webling"),
                        show: can("export-to-webling")
                    } : null,
                    {
                        to: { name: "accounting.index" },
                        icon: "home",
                        text: i18n.t("Overview"),
                        show:
                            can("view-accounting-summary") ||
                            can("view-transactions")
                    }
                ]
            })
        }
    },
    {
        path: "/accounting/transactions/create",
        name: "accounting.transactions.create",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/TransactionCreatePage.vue"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Register new transaction"),
                container: true
            }
        }
    },
    {
        path: "/accounting/transactions/:id",
        name: "accounting.transactions.show",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/TransactionViewPage.vue"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Show transaction"),
                container: true
            }
        }
    },
    {
        path: "/accounting/transactions/:id/edit",
        name: "accounting.transactions.edit",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/TransactionEditPage.vue"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Edit transaction"),
                container: true
            }
        }
    },
    {
        path: "/accounting/budgets",
        name: "accounting.budgets.index",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/BudgetsIndexPage.vue"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Budgets"),
                buttons: [
                    {
                        to: { name: "accounting.budgets.create" },
                        variant: "primary",
                        icon: "plus-circle",
                        text: i18n.t("Add"),
                        // show: can("configure-accounting")
                    },
                    {
                        to: { name: "accounting.index" },
                        icon: "home",
                        text: i18n.t("Overview"),
                        show:
                            can("view-accounting-summary") ||
                            can("view-transactions")
                    }
                ],
                container: true
            }
        },
    },
    {
        path: "/accounting/budgets/create",
        name: "accounting.budgets.create",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/BudgetCreatePage.vue"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Create budget"),
                container: true
            }
        }
    },
    {
        path: "/accounting/budgets/:id",
        name: "accounting.budgets.show",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/BudgetShowPage.vue"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Show budget"),
                container: true
            }
        }
    },
    {
        path: "/accounting/budgets/:id/edit",
        name: "accounting.budgets.edit",
        components: {
            default: () =>
                import(
                    "@/pages/accounting/BudgetEditPage.vue"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Edit budget"),
                container: true
            }
        }
    },
];
