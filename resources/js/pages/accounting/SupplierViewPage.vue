<template>
    <b-container
        v-if="supplier"
        fluid
        class="px-0"
    >
        <b-list-group class="mb-3">

            <two-col-list-group-item
                :title="$t('app.name')"
            >
                {{ supplier.name }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.category"
                :title="$t('app.category')"
            >
                {{ supplier.category }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.address"
                :title="$t('app.address')"
            >
                <maps-link
                    :label="supplier.address"
                    :query="supplier.address"
                    :place-id="supplier.place_id"
                />
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.phone"
                :title="$t('app.phone')"
            >
                <phone-link
                  :value="supplier.phone"
                />
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.mobile"
                :title="$t('app.mobile')"
            >
                <phone-link
                  :value="supplier.mobile"
                  mobile
                />
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.email"
                :title="$t('app.email')"
            >
                <email-link
                  :value="supplier.email"
                />
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.website"
                :title="$t('app.website')"
            >
                <a :href="supplier.website" target="_blank">{{ supplier.website }}</a>
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.tax_number"
                :title="$t('accounting.tax_number')"
            >
                {{ supplier.tax_number }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.tax_office"
                :title="$t('accounting.tax_office')"
            >
                {{ supplier.tax_office }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.bank"
                :title="$t('accounting.bank')"
            >
                {{ supplier.bank }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.iban"
                :title="$t('accounting.iban')"
            >
                {{ supplier.iban }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.remarks"
                :title="$t('app.remarks')"
            >
                {{ supplier.remarks }}
            </two-col-list-group-item>

        </b-list-group>

        <h4>{{ $t('accounting.transactions') }}</h4>
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

    </b-container>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import moment from 'moment'
import numeral from 'numeral'
import suppliersApi from '@/api/accounting/suppliers'
import TwoColListGroupItem from '@/components/ui/TwoColListGroupItem'
import PhoneLink from '@/components/common/PhoneLink'
import EmailLink from '@/components/common/EmailLink'
import MapsLink from '@/components/common/MapsLink'
import BaseTable from '@/components/table/BaseTable'
export default {
    components: {
        TwoColListGroupItem,
        EmailLink,
        PhoneLink,
        MapsLink,
        BaseTable
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            supplier: null,
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