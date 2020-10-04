<template>
    <base-table
        ref="table"
        id="suppliers-transactons-table"
        :fields="transactionFields"
        :api-method="fetchTransactions"
        default-sort-by="created_at"
        default-sort-desc
        :empty-text="$t('app.no_data_registered')"
        :items-per-page="25"
    >
    </base-table>
</template>

<script>
import moment from 'moment'
import numeral from 'numeral'
import suppliersApi from '@/api/accounting/suppliers'
import BaseTable from '@/components/table/BaseTable'
export default {
    components: {
        BaseTable
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            transactionFields: [
                {
                    key: 'receipt_no',
                    label: this.$t('accounting.receipt_no'),
                    sortable: true,
                    class: 'text-right fit'
                },    
                {
                    key: 'date',
                    label: this.$t('app.date'),
                    sortable: true,
                    formatter: this.dateFormat,
                    class: 'fit'
                },            
                {
                    key: 'amount',
                    label: this.$t('app.amount'),
                    formatter: (value, key, item) => {
                        let val = value
                        if (item.type == 'spending') {
                            val = -val
                        }
                        return numeral(val).format('0,0.00')
                    },
                    class: 'text-right fit',
                    tdClass: (value, key, item) => {
                        if (item.type == 'spending') {
                            return 'text-danger'
                        }
                        return 'text-success'
                    }
                },
                {
                    key: 'category',
                    label: this.$t('app.category')
                },
                {
                    key: 'description',
                    label: this.$t('app.description')
                },
                {
                    key: 'attendee',
                    label: this.$t('accounting.attendee')
                },
                {
                    key: 'created_at',
                    label: this.$t('app.registered'),
                    sortable: true,
                    formatter: this.dateTimeFormat,
                    class: 'fit'
                }                                                                 
            ]            
        }
    },
    methods: {
        fetchTransactions (ctx) {
            return suppliersApi.transactions(this.id, ctx)
        },
        dateFormat (value) {
            return moment(value).format('LL')
        },
        dateTimeFormat (value) {
            return moment(value).format('LLL')
        }
    }
}
</script>