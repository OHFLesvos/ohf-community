<template>
    <b-container
        v-if="supplier"
        fluid
        class="px-0"
    >
        <h2>{{ $t('accounting.edit_supplier') }}</h2>
        <supplier-form
            :supplier="supplier" 
            :disabled="isBusy"
            @submit="updateSupplier"
            @cancel="handleCnacel"
            @delete="deleteSupplier"          
        />
        <hr>
        <p class="text-right">
            <small>
                {{ $t('app.last_updated') }}:
                {{ dateFormat(supplier.updated_at) }}
            </small>
        </p>
    </b-container>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import moment from 'moment'
import { showSnackbar } from '@/utils'
import suppliersApi from '@/api/accounting/suppliers'
import SupplierForm from '@/components/accounting/SupplierForm'
export default {
    components: {
        SupplierForm
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            supplier: null,
            isBusy: false
        }
    },
    watch: {
        $route() {
            this.fetchSupplier()
        }
    },    
    async created () {
        this.fetchSupplier()
    },
    methods: {
        async fetchSupplier () {
            try {
                let data = await suppliersApi.find(this.id)
                this.supplier = data.data
            } catch (err) {
                alert(err)
            }
        },
        async updateSupplier (formData) {
            this.isBusy = true
            try {
                let data = await suppliersApi.update(this.id, formData)
                showSnackbar(this.$t('accounting.supplier_updated'))
                this.$router.push({ name: 'accounting.suppliers.show', parms: { id: this.supplier.id } })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async deleteSupplier () {
            this.isBusy = true
            try {
                let data = await suppliersApi.delete(this.id)
                showSnackbar(this.$t('accounting.supplier_deleted'))
                this.$router.push({ name: 'accounting.suppliers.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCnacel () {
            this.$router.push({ name: 'accounting.suppliers.show', parms: { id: this.supplier.id } })
        },
        dateFormat (value) {
            return moment(value).format('LLL')
        }
    }
}
</script>