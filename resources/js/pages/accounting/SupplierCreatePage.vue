<template>
    <b-container
        fluid
        class="px-0"
    >
        <supplier-form
            :disabled="isBusy"
            @submit="registerSupplier"
            @cancel="handleCnacel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import suppliersApi from '@/api/accounting/suppliers'
import SupplierForm from '@/components/accounting/SupplierForm'
export default {
    components: {
        SupplierForm
    },
    data () {
        return {
            isBusy: false
        }
    },
    methods: {
        async registerSupplier (formData) {
            this.isBusy = true
            try {
                let data = await suppliersApi.store(formData)
                showSnackbar(this.$t('accounting.supplier_registered'))
                this.$router.push({ name: 'accounting.suppliers.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCnacel () {
            this.$router.push({ name: 'accounting.suppliers.index' })
        }
    }
}
</script>