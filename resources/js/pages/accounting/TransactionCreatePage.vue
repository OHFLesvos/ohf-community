<template>
    <b-container v-if="walletData" class="px-0">
        <TransactionForm
            :defaultReceiptNumber="walletData.next_free_receipt"
            :disabled="isBusy"
            :use-secondary-categories="settings['accounting.transactions.use_secondary_categories']"
            :use-locations="settings['accounting.transactions.use_locations']"
            :use-cost-centers="settings['accounting.transactions.use_cost_centers']"
            @submit="handleCreate"
            @cancel="handleCancel"
        />
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import { showSnackbar } from "@/utils";
import transactionsApi from "@/api/accounting/transactions";
import walletsApi from "@/api/accounting/wallets";
import TransactionForm from "@/components/accounting/TransactionForm";
import { mapState } from "vuex";
export default {
    title() {
        return this.$t("Register new transaction");
    },
    components: {
        TransactionForm
    },
    props: {
        wallet: {
            required: true
        }
    },
    data() {
        return {
            isBusy: false,
            walletData: null
        };
    },
    computed: mapState(["settings"]),
    async created() {
        this.walletData = (await walletsApi.find(this.wallet)).data;
    },
    methods: {
        async handleCreate(formData) {
            this.isBusy = true;
            try {
                let data = await transactionsApi.store(this.wallet, formData);
                showSnackbar(this.$t("Transaction registered."));
                this.$router.push({
                    name: "accounting.transactions.show",
                    params: { id: data.id }
                });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({
                name: "accounting.transactions.index",
                params: { wallet: this.wallet }
            });
        }
    }
};
</script>
