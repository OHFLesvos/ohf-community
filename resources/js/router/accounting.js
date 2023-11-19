import i18n from "@/plugins/i18n";

import { can } from "@/plugins/laravel";

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
            default: () => import("@/pages/accounting/SuppliersIndexPage.vue"),
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
                        text: i18n.t("Suppliers"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/suppliers/create",
        name: "accounting.suppliers.create",
        components: {
            default: () =>import("@/pages/accounting/SupplierCreatePage.vue"),
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
                        text: i18n.t("Suppliers"),
                        to: { name: 'accounting.suppliers.index' },
                    },
                    {
                        text: i18n.t("Register supplier"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/suppliers/:id",
        components: {
            default: () => import("@/pages/accounting/SupplierViewPage.vue"),
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
                        text: i18n.t("Suppliers"),
                        to: { name: 'accounting.suppliers.index' },
                    },
                    {
                        text: i18n.t("Supplier"),
                    }
                ]
            }
        },
        children: [
            {
                path: "",
                name: "accounting.suppliers.show",
                component: () => import("@/components/accounting/SupplierDetails.vue"),
                props: true
            },
            {
                path: "transactions",
                name: "accounting.suppliers.show.transactions",
                component: () => import("@/components/accounting/SupplierTransactions.vue"),
                props: true
            }
        ]
    },
    {
        path: "/accounting/suppliers/:id/edit",
        name: "accounting.suppliers.edit",
        components: {
            default: () => import("@/pages/accounting/SupplierEditPage.vue"),
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
                        text: i18n.t("Suppliers"),
                        to: { name: 'accounting.suppliers.index' },
                    },
                    {
                        text: i18n.t("Edit supplier"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions/summary",
        name: "accounting.transactions.summary",
        components: {
            default: () => import("@/pages/accounting/SummaryPage.vue"),
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
                        text: i18n.t("Summary"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions/history",
        name: "accounting.transactions.history",
        components: {
            default: () => import("@/pages/accounting/TransactionsHistoryPage.vue"),
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
                        text: i18n.t("History"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions/controlling",
        name: "accounting.transactions.controlling",
        components: {
            default: () => import("@/pages/accounting/TransactionsControllingPage.vue"),
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
                        text: i18n.t("Controlling"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions",
        name: "accounting.transactions.index",
        components: {
            default: () => import("@/pages/accounting/TransactionsIndexPage.vue"),
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
                        text: i18n.t("Transactions"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions/create",
        name: "accounting.transactions.create",
        components: {
            default: () => import("@/pages/accounting/TransactionCreatePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: route => ({
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Transactions"),
                        to: {
                            name: 'accounting.transactions.index',
                            query: { wallet: route.query.wallet },
                        },
                    },
                    {
                        text: i18n.t("Register new transaction"),
                    }
                ]
            })
        }
    },
    {
        path: "/accounting/transactions/:id",
        name: "accounting.transactions.show",
        components: {
            default: () => import("@/pages/accounting/TransactionViewPage.vue"),
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
                        text: i18n.t("Transactions"),
                        to: {
                            name: 'accounting.transactions.index',
                        },
                    },
                    {
                        text: i18n.t("Details"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/transactions/:id/edit",
        name: "accounting.transactions.edit",
        components: {
            default: () => import("@/pages/accounting/TransactionEditPage.vue"),
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
                        text: i18n.t("Transactions"),
                        to: {
                            name: 'accounting.transactions.index',
                        },
                    },
                    {
                        text: i18n.t("Edit transaction"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/budgets",
        name: "accounting.budgets.index",
        components: {
            default: () => import("@/pages/accounting/BudgetsIndexPage.vue"),
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
                        text: i18n.t("Budgets"),
                    }
                ]
            }
        },
    },
    {
        path: "/accounting/budgets/create",
        name: "accounting.budgets.create",
        components: {
            default: () => import("@/pages/accounting/BudgetCreatePage.vue"),
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
                        text: i18n.t("Budgets"),
                        to: { name: 'accounting.budgets.index' },
                    },
                    {
                        text: i18n.t("Create budget"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/budgets/:id",
        name: "accounting.budgets.show",
        components: {
            default: () => import("@/pages/accounting/BudgetShowPage.vue"),
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
                        text: i18n.t("Budgets"),
                        to: { name: 'accounting.budgets.index' },
                    },
                    {
                        text: i18n.t("Details"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/budgets/:id/edit",
        name: "accounting.budgets.edit",
        components: {
            default: () => import("@/pages/accounting/BudgetEditPage.vue"),
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
                        text: i18n.t("Budgets"),
                        to: { name: 'accounting.budgets.index' },
                    },
                    {
                        text: i18n.t("Edit budget"),
                    }
                ]
            }
        }
    },
    {
        path: "/accounting/wallets/:wallet/weblings",
        name: "accounting.webling.index",
        components: {
            default: () => import("@/pages/accounting/webling/WeblingIndexPage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: route => ({
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Transactions"),
                        to: {
                            name: 'accounting.transactions.index',
                            query: { wallet: route.params.wallet },
                        },
                    },
                    {
                        text: i18n.t('Book to Webling'),
                    }
                ]
            })
        }
    },
    {
        path: "/accounting/wallets/:wallet/weblings/prepare",
        name: "accounting.webling.prepare",
        components: {
            default: () => import("@/pages/accounting/webling/WeblingPreparePage.vue"),
            breadcrumbs: BreadcrumbsNav,
        },
        props: {
            default: true,
            breadcrumbs: route => ({
                items: [
                    {
                        text: i18n.t('Accounting'),
                        to: { name: 'accounting.index' },
                        show: can("view-accounting-summary") || can("view-transactions")
                    },
                    {
                        text: i18n.t("Transactions"),
                        to: {
                            name: 'accounting.transactions.index',
                            query: { wallet: route.params.wallet },
                        },
                    },
                    {
                        text: i18n.t('Book to Webling'),
                        to: {
                            name: 'accounting.webling.index',
                            params: { id: route.params.wallet }
                        },
                    },
                    {
                        text: i18n.t('Period'),
                    }
                ]
            })
        }
    },
];
