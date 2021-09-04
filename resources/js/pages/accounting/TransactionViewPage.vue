<template>
    <b-container v-if="transaction" class="px-0">
        <b-tabs justified>
            <b-tab :title="$t('Details')" active>
                <TransactionDetails :transaction="transaction" />
            </b-tab>
            <b-tab :title="$t('History')" lazy>
                <TransactionHistory
                    :entries="fetchHistory"
                />
            </b-tab>
        </b-tabs>

        <p>
            <router-link
                v-if="transaction.can_update"
                :to="{
                    name: 'accounting.transactions.edit',
                    params: { id: id }
                }"
                class="btn btn-primary"
            >
                <font-awesome-icon icon="edit" /> {{ $t("Edit") }}</router-link
            >
            <router-link
                :to="{
                    name: 'accounting.transactions.index',
                    params: { wallet: transaction.wallet_id }
                }"
                class="btn btn-secondary"
            >
                <font-awesome-icon icon="times-circle" />
                {{ $t("Overview") }}
            </router-link>
        </p>
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import transactionsApi from "@/api/accounting/transactions";
import TransactionDetails from "@/components/accounting/TransactionDetails";
import TransactionHistory from "@/components/accounting/TransactionHistory";
import TabNav from "@/components/layout/TabNav";
export default {
    components: {
        TransactionDetails,
        TransactionHistory,
        TabNav
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            transaction: null,
            isBusy: false
        };
    },
    watch: {
        $route() {
            this.fetch();
        }
    },
    async created() {
        this.fetch();
    },
    methods: {
        async fetch() {
            try {
                let data = await transactionsApi.find(this.id);
                this.transaction = data.data;
            } catch (err) {
                alert(err);
                console.error(err);
            }
        },
        async fetchHistory() {
            try {
                let data = await transactionsApi.transactionHistory(this.id);
                return data.data;
            } catch (err) {
                alert(err);
                console.error(err);
            }
        }
    }
};
</script>
