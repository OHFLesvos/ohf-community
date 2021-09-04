<template>
    <div>
        <TransactionHistory :entries="fetchHistory" showId />
    </div>
</template>

<script>
import transactionsApi from "@/api/accounting/transactions";
import TransactionHistory from "@/components/accounting/TransactionHistory";
export default {
    title() {
        return this.$t("History");
    },
    components: {
        TransactionHistory
    },
    data() {
        return {};
    },
    methods: {
        async fetchHistory() {
            try {
                let data = await transactionsApi.history();
                return data.data;
            } catch (err) {
                alert(err);
                console.error(err);
            }
        }
    }
};
</script>
