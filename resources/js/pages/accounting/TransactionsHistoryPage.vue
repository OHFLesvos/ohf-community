<template>
    <b-container fluid>
        <b-row class="mb-3">
            <b-col cols="auto">
                <b-input-group :prepend="$t('Date')">
                    <b-form-datepicker
                        v-model="date"
                        reset-button
                        today-button
                    />
                </b-input-group>
            </b-col>
        </b-row>
        <TransactionHistory ref="history" :entries="fetchHistory" showId />
    </b-container>
</template>

<script>
import transactionsApi from "@/api/accounting/transactions";
import TransactionHistory from "@/components/accounting/TransactionHistory.vue";
export default {
    title() {
        return this.$t("History");
    },
    components: {
        TransactionHistory
    },
    data() {
        return {
            date: null
        };
    },
    watch: {
        date() {
            this.$refs.history.refresh();
        }
    },
    methods: {
        async fetchHistory(ctx) {
            if (this.date) {
                ctx.date = this.date;
            }
            return await transactionsApi.history(ctx);
        }
    }
};
</script>
