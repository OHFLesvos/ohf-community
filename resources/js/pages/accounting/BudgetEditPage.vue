<template>
    <b-container v-if="budget">
        <BudgetForm
            :budget="budget"
            :title="$t('Edit budget')"
            :disabled="isBusy"
            @submit="updateWallet"
            @cancel="handleCancel"
            @delete="deleteWallet"
        />
        <p class="text-right">
            <small>
                {{ $t("Last updated") }}:
                {{ budget.updated_at | dateTimeFormat }}
            </small>
        </p>
    </b-container>
    <b-container v-else>
        {{ $t("Loading...") }}
    </b-container>
</template>

<script>
import { showSnackbar } from "@/utils";
import budgetsApi from "@/api/accounting/budgets";
import BudgetForm from "@/components/accounting/BudgetForm.vue";
export default {
    title() {
        return this.$t("Edit budget");
    },
    components: {
        BudgetForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            budget: null,
            isBusy: false
        };
    },
    watch: {
        $route() {
            this.fetchWallet();
        }
    },
    async created() {
        this.fetchWallet();
    },
    methods: {
        async fetchWallet() {
            try {
                let data = await budgetsApi.find(this.id);
                this.budget = data.data;
            } catch (err) {
                alert(err);
            }
        },
        async updateWallet(formData) {
            this.isBusy = true;
            try {
                await budgetsApi.update(this.id, formData);
                showSnackbar(this.$t("Budget updated."));
                this.$router.push({
                    name: "accounting.budgets.show",
                    params: { id: this.id }
                });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async deleteWallet() {
            this.isBusy = true;
            try {
                await budgetsApi.delete(this.id);
                showSnackbar(this.$t("Budget deleted."));
                this.$router.push({ name: "accounting.budgets.index" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({
                name: "accounting.budgets.show",
                params: { id: this.id }
            });
        }
    }
};
</script>
