<template>
    <b-container
        class="px-0"
    >
        <budget-form
            :disabled="isBusy"
            @submit="handleCreate"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import budgetsApi from "@/api/accounting/budgets";
import BudgetForm from "@/components/accounting/BudgetForm";
export default {
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
