<template>
    <div>
        <tab-nav :items="tabNavItems">
            <template v-slot:after(transactions)>
                <b-badge
                    v-if="transactionsCount > 0"
                    class="ml-1"
                >
                    {{ transactionsCount }}
                </b-badge>
            </template>                  
        </tab-nav>
        <router-view />
    </div>
</template>

<script>
import TabNav from '@/components/ui/TabNav'
import suppliersApi from '@/api/accounting/suppliers'
export default {
    components: {
        TabNav
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            transactionsCount: null,
            tabNavItems: [
                {
                    to: { name: 'accounting.suppliers.show' },
                    icon: 'info',
                    text: this.$t('app.details')
                },
                {
                    to: { name: 'accounting.suppliers.show.transactions' },
                    icon: 'list',
                    text: this.$t('accounting.transactions'),
                    key: 'transactions'
                },
            ]            
        }
    },
    watch: {
        $route() {
            this.fetchData()
        }
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            try {
                let data = await suppliersApi.find(this.id)
                this.transactionsCount = data.data.transactions_count
            } catch (err) {
                this.error = err
            }
        },
    }    
}
</script>