<template>
    <b-container fluid>
        <PageHeader :buttons="navButtons"/>
        <TransactionsTable
            :use-secondary-categories="
                settings['accounting.transactions.use_secondary_categories']
            "
            :use-locations="settings['accounting.transactions.use_locations']"
            :use-cost-centers="
                settings['accounting.transactions.use_cost_centers']
            "
            :show-intermediate-balances="
                settings['accounting.transactions.show_intermediate_balances']
            "
            @change="updateQueryString"
        />
    </b-container>
</template>

<script>
import TransactionsTable from "@/components/accounting/TransactionsTable.vue";
import PageHeader from "@/components/layout/PageHeader.vue";
import { mapState } from "vuex";
export default {
    title() {
        return this.$t("Transactions");
    },
    components: {
        TransactionsTable,
        PageHeader,
    },
    computed: {
        navButtons() {
            return [
                {
                    to: {
                        name: "accounting.transactions.create",
                        query: { wallet: this.$route.query.wallet ?? null }
                    },
                    icon: "plus-circle",
                    text: this.$t("Add"),
                    variant: "primary",
                    show: this.can("create-transactions")
                },
                {
                    to: {
                        name: "accounting.transactions.summary",
                        query: { wallet: this.$route.query.wallet ?? null }
                    },
                    icon: "calculator",
                    text: this.$t("Summary"),
                    show: this.can("view-accounting-summary")
                },
                this.$route.query.wallet ? {
                    to: {
                        name: 'accounting.webling.index',
                        params: { wallet: this.$route.query.wallet }
                    },
                    icon: "cloud-upload-alt",
                    text: this.$t("Webling"),
                    show: this.can("export-to-webling")
                } : null,
            ]
        },
        ...mapState(["settings"]),
    },
    methods: {
        updateQueryString(params) {
            this.$router.push(
                {
                    query: {
                        ...this.$route.query,
                        ...params
                    }
                },
                () => {}
            );
        }
    }
};
</script>
