<template>
    <b-container>
        <BudgetForm
            :disabled="isBusy"
            :title="$t('Create budget')"
            @submit="handleCreate"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import budgetsApi from "@/api/accounting/budgets";
import BudgetForm from "@/components/accounting/BudgetForm.vue";
export default {
    title() {
        return this.$t("Create budget");
    },
    components: {
        BudgetForm
    },
    data () {
        return {
            isBusy: false
        }
    },
    methods: {
        async handleCreate (formData) {
            this.isBusy = true
            try {
                await budgetsApi.store(formData)
                showSnackbar(this.$t('Budget added.'))
                this.$router.push({ name: 'accounting.budgets.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCancel () {
            this.$router.push({ name: 'accounting.budgets.index' })
        }
    }
}
</script>
