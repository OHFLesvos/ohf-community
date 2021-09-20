<template>
    <div>
        <TransactionsTable
            :wallet="wallet"
            :use-secondary-categories="settings['accounting.transactions.use_secondary_categories']"
            :use-locations="settings['accounting.transactions.use_locations']"
            :use-cost-centers="settings['accounting.transactions.use_cost_centers']"
            :show-intermediate-balances="settings['accounting.transactions.show_intermediate_balances']"
        />
    </div>
</template>

<script>
import TransactionsTable from "@/components/accounting/TransactionsTable";
import { mapState } from "vuex";
export default {
    title() {
        return this.$t("Transactions");
    },
    components: {
        TransactionsTable
    },
    props: {
        wallet: {
            required: true
        }
    },
    computed: mapState(["settings"]),
};
</script>
