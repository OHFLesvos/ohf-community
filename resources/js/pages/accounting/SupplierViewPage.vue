<template>
    <b-container
        v-if="supplier"
        fluid
        class="px-0"
    >
        <supplier-details :supplier="supplier" />
        <h4>{{ $t('accounting.transactions') }}</h4>
        <supplier-transactions :supplier-id="id" />
    </b-container>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import suppliersApi from '@/api/accounting/suppliers'
import SupplierDetails from '@/components/accounting/SupplierDetails'
import SupplierTransactions from '@/components/accounting/SupplierTransactions'
export default {
    components: {
        SupplierDetails,
        SupplierTransactions
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            supplier: null
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
        }
    }  
}
</script>