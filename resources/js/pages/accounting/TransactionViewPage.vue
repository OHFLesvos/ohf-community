<template>
    <b-container v-if="transaction" class="px-0">
        <b-tabs justified>
            <b-tab :title="$t('Details')" active>
                <TransactionDetails
                    id="transaction-details-table"
                    :transaction="transaction"
                    :disabled="isBusy"
                    @update="fetch"
                />
            </b-tab>
            <b-tab :title="$t('History')" lazy>
                <TransactionHistory :entries="fetchHistory" />
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
                v-else-if="transaction.can_update_metadata"
                :to="{
                    name: 'accounting.transactions.edit',
                    params: { id: id }
                }"
                class="btn btn-primary"
            >
                <font-awesome-icon icon="edit" /> {{ $t("Edit metadata") }}</router-link
            >
            <b-button
                variant="primary"
                @click="print()"
            >
                <font-awesome-icon :icon="['fa', 'print']" />
                {{ $t("Print") }}
            </b-button>
            <router-link
                :to="{
                    name: 'accounting.transactions.index',
                    query: { wallet: transaction.wallet_id }
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
import TransactionDetails from "@/components/accounting/TransactionDetails.vue";
import TransactionHistory from "@/components/accounting/TransactionHistory.vue";
export default {
    title() {
        return this.$t("Show transaction");
    },
    components: {
        TransactionDetails,
        TransactionHistory,
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
            this.isBusy = true;
            try {
                let data = await transactionsApi.find(this.id);
                this.transaction = data.data;
            } catch (err) {
                alert(err);
                console.error(err);
            }
            this.isBusy = false;
        },
        async fetchHistory() {
            return await transactionsApi.transactionHistory(this.id);
        },
        async print() {
            await this.$htmlToPaper("transaction-details-table", {
                // There seems to be no way of obtaining urls of compiled resources in vite,
                // so the style sheet urls are just copied from the main document
                styles: [...document.styleSheets].map(css => css.href).filter(url => url),
            })
        }
    }
};
</script>
