<template>
    <b-container class="px-0">
        <TransactionForm
            :disabled="isBusy"
            :use-secondary-categories="
                settings['accounting.transactions.use_secondary_categories']
            "
            :use-locations="settings['accounting.transactions.use_locations']"
            :use-cost-centers="
                settings['accounting.transactions.use_cost_centers']
            "
            @submit="handleCreate"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from "@/utils";
import transactionsApi from "@/api/accounting/transactions";
import TransactionForm from "@/components/accounting/TransactionForm.vue";
import { mapState } from "vuex";
export default {
    title() {
        return this.$t("Register new transaction");
    },
    components: {
        TransactionForm
    },
    data() {
        return {
            isBusy: false,
        };
    },
    computed: mapState(["settings"]),
    methods: {
        async handleCreate(formData) {
            this.isBusy = true;
            try {
                let data = await transactionsApi.store(formData);
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
                query: { wallet: this.$route.query.wallet ?? null }
            });
        }
    }
};
</script>
