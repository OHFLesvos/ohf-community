<template>
    <b-container v-if="transaction">
        <TransactionForm
            :transaction="transaction"
            :disabled="isBusy"
            :title="$t('Edit transaction')"
            :use-secondary-categories="settings['accounting.transactions.use_secondary_categories']"
            :use-locations="settings['accounting.transactions.use_locations']"
            :use-cost-centers="settings['accounting.transactions.use_cost_centers']"
            @submit="handleUpdate"
            @delete="handleDelete"
            @cancel="handleCancel"
        />
    </b-container>
    <b-container v-else>
        {{ $t("Loading...") }}
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import transactionsApi from "@/api/accounting/transactions";
import TransactionForm from "@/components/accounting/TransactionForm.vue";
import { mapState } from "vuex";
export default {
    title() {
        return this.$t("Edit transaction");
    },
    components: {
        TransactionForm
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
    computed: mapState(["settings"]),
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
        async handleUpdate(formData) {
            this.isBusy = true;
            try {
                await transactionsApi.update(this.id, formData);
                showSnackbar(this.$t("Transaction updated."));
                this.$router.push({
                    name: "accounting.transactions.show",
                    params: { id: this.id }
                });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async handleDelete() {
            this.isBusy = true;
            try {
                await transactionsApi.delete(this.id);
                showSnackbar(this.$t("Transaction deleted."));
                this.$router.push({
                    name: "accounting.transactions.index",
                    query: { wallet: this.transaction.wallet_id }
                });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({
                name: "accounting.transactions.show",
                params: { id: this.id }
            });
        }
    }
};
</script>
