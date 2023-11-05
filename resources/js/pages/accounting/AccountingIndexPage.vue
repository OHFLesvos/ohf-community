<template>
    <b-container>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <b-card :header="$t('Wallets')" class="shadow-sm mb-4" no-body>
            <b-list-group flush>
                <template v-if="wallets && wallets.length > 0">
                    <b-list-group-item
                        v-for="wallet in wallets"
                        :key="wallet.id" :to="can('view-transactions') ? { name: 'accounting.transactions.index', query: { wallet: wallet.id } } : null"
                    >
                        {{ wallet.name }}
                        <span :class="{'float-right': true, 'text-danger': wallet.amount < 0}">{{ wallet.amount_formatted }}</span>
                    </b-list-group-item>
                </template>
                <b-list-group-item v-else-if="wallets">
                    {{ $t('No wallets found.') }}
                </b-list-group-item>
                <b-list-group-item v-else>
                    {{ $t('Loading...') }}
                </b-list-group-item>
            </b-list-group>
        </b-card>
        <b-row>
            <b-col
                v-for="(button, idx) in buttons.filter(btn => btn.show)" :key="idx"
                sm="6" md="4" lg="3" class="mb-4"
            >
                <b-button
                    class="d-block"
                    :variant="button.variant"
                    :to="button.to"
                >
                    <font-awesome-icon :icon="button.icon" />
                    {{ button.text }}</b-button
                >
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import AlertWithRetry from "@/components/alerts/AlertWithRetry.vue";
import walletsApi from "@/api/accounting/wallets";
export default {
    title() {
        return this.$t("Accounting");
    },
    components: {
        AlertWithRetry
    },
    data() {
        return {
            errorText: null,
            wallets: null,
            buttons: [
                {
                    to: {
                        name: "accounting.transactions.summary"
                    },
                    icon: "calculator",
                    text: this.$t("Summary"),
                    show: this.can("view-accounting-summary")
                },
                {
                    to: { name: "accounting.categories.index" },
                    icon: "tag",
                    text: this.$t("Categories"),
                    show: this.can("view-accounting-categories")
                },
                {
                    to: { name: "accounting.projects.index" },
                    icon: "project-diagram",
                    text: this.$t("Projects"),
                    show: this.can("view-accounting-projects")
                },
                {
                    to: { name: "accounting.suppliers.index" },
                    variant: "secondary",
                    icon: "truck",
                    text: this.$t("Suppliers"),
                    show:
                        this.can("view-suppliers") ||
                        this.can("manage-suppliers")
                },
                {
                    to: { name: "accounting.budgets.index" },
                    variant: "secondary",
                    icon: "money-bill-alt",
                    text: this.$t("Budgets"),
                    show: this.can("view-budgets") || this.can("manage-budgets")
                },
                {
                    to: { name: "accounting.wallets.index" },
                    variant: "secondary",
                    icon: "wallet",
                    text: this.$t("Wallets"),
                    show: this.can("configure-accounting")
                },
                {
                    to: { name: "accounting.transactions.history" },
                    variant: "secondary",
                    icon: "history",
                    text: this.$t("History"),
                    show: this.can("view-transactions")
                },
                {
                    to: { name: "accounting.transactions.controlling" },
                    variant: "secondary",
                    icon: "check",
                    text: this.$t("Controlling"),
                    show: this.can("view-transactions")
                }
            ]
        };
    },
    async created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.errorText = null;
            try {
                this.wallets = await walletsApi.names()
            } catch (ex) {
                this.errorText = ex;
            }
        },
    }
};
</script>
