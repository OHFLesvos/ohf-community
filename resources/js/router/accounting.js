import i18n from "@/plugins/i18n";
import ziggyRoute from "@/plugins/ziggy";

import { can } from "@/plugins/laravel";

import PageHeader from "@/components/layout/PageHeader";

export default [
    {
        path: "/accounting",
        name: "accounting.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/AccountingIndexPage"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Overview"),
                container: true
            }
        }
    },
    {
        path: "/accounting/wallets",
        name: "accounting.wallets.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/WalletsIndexPage"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Wallets"),
                buttons: [
                    {
                        to: { name: "accounting.wallets.create" },
                        variant: "primary",
                        icon: "plus-circle",
                        text: i18n.t("Add"),
                        show: can("configure-accounting")
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
        }
    },
    {
        path: "/accounting/wallets/create",
        name: "accounting.wallets.create",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/WalletCreatePage"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Create wallet"),
                container: true
            }
        }
    },
    {
        path: "/accounting/wallets/:id/edit",
        name: "accounting.wallets.edit",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/WalletEditPage"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Edit wallet"),
                container: true
            }
        }
    },
    {
        path: "/accounting/categories",
        name: "accounting.categories.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/CategoryIndexPage"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Categories"),
                buttons: [
                    {
                        to: { name: "accounting.categories.create" },
                        variant: "primary",
                        icon: "plus-circle",
                        text: i18n.t("Add"),
                        show: can("configure-accounting")
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
        }
    },
    {
        path: "/accounting/categories/create",
        name: "accounting.categories.create",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/CategoryCreatePage"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Create category"),
                container: true
            }
        }
    },
    {
        path: "/accounting/categories/:id",
        name: "accounting.categories.show",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/CategoryViewPage"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("View category"),
                container: true
            }
        }
    },
    {
        path: "/accounting/categories/:id/edit",
        name: "accounting.categories.edit",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/CategoryEditPage"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Edit category"),
                container: true
            }
        }
    },
    {
        path: "/accounting/projects",
        name: "accounting.projects.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/ProjectIndexPage"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Projects"),
                buttons: [
                    {
                        to: { name: "accounting.projects.create" },
                        variant: "primary",
                        icon: "plus-circle",
                        text: i18n.t("Add"),
                        show: can("configure-accounting")
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
        }
    },
    {
        path: "/accounting/projects/create",
        name: "accounting.projects.create",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/ProjectCreatePage"
                ),
            header: PageHeader
        },
        props: {
            header: {
                title: i18n.t("Create project"),
                container: true
            }
        }
    },
    {
        path: "/accounting/projects/:id/edit",
        name: "accounting.projects.edit",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/ProjectEditPage"
                ),
            header: PageHeader
        },
        props: {
            default: true,
            header: {
                title: i18n.t("Edit project"),
                container: true
            }
        }
    },
    {
        path: "/accounting/suppliers",
        name: "accounting.suppliers.index",
        components: {
            default: () =>
                import(
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/SuppliersIndexPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/SupplierCreatePage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/SupplierViewPage"
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
                        /* webpackChunkName: "accounting" */ "@/components/accounting/SupplierDetails"
                    ),
                props: true
            },
            {
                path: "transactions",
                name: "accounting.suppliers.show.transactions",
                component: () =>
                    import(
                        /* webpackChunkName: "accounting" */ "@/components/accounting/SupplierTransactions"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/SupplierEditPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/SummaryPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/TransactionsHistoryPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/TransactionsControllingPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/TransactionsIndexPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/TransactionCreatePage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/TransactionViewPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/TransactionEditPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/BudgetsIndexPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/BudgetCreatePage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/BudgetShowPage"
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
                    /* webpackChunkName: "accounting" */ "@/pages/accounting/BudgetEditPage"
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
