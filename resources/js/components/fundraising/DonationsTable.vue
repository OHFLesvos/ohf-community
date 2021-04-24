<template>
    <base-table
        id="donationsTable"
        :fields="fields"
        :api-method="list"
        default-sort-by="created_at"
        default-sort-desc
        :empty-text="$t('app.no_donations_found')"
        :filter-placeholder="$t('app.search_ellipsis')"
        :items-per-page="100"
    >
        <!-- Date / Link to edit -->
        <template v-slot:cell(date)="data">
            <slot name="primary-cell" :value="data.value" :item="data.item">
                {{ data.value }}
            </slot>
        </template>

        <!-- Amount -->
        <template v-slot:cell(exchange_amount)="data">
            <small
                v-if="data.item.currency != baseCurrency"
                class="text-muted ml-1"
            >
                {{ data.item.currency }} {{ numberFormat(data.item.amount) }}
            </small>
            {{ baseCurrency }} {{ numberFormat(data.value) }}
        </template>

        <!-- Donor -->
        <template v-slot:cell(donor)="data">
            <slot name="donor-cell" :value="data.value" :item="data.item">
                {{ data.value }}
            </slot>
        </template>

        <!-- Thanked -->
        <template v-slot:head(thanked)>
            <font-awesome-icon icon="handshake" />
        </template>
        <template v-slot:cell(thanked)="data">
            <font-awesome-icon v-if="data.value" icon="check" />
        </template>
    </base-table>
</template>

<script>
import moment from 'moment'
import numeral from 'numeral'
import BaseTable from '@/components/table/BaseTable'
import donationsApi from '@/api/fundraising/donations'
export default {
    components: {
        BaseTable
    },
    data() {
        return {
            fields: [
                {
                    key: 'date',
                    label: this.$t('app.date'),
                    class: 'fit',
                    sortable: true,
                    sortDirection: 'desc'
                },
                {
                    key: 'exchange_amount',
                    label: this.$t('app.amount'),
                    class: 'text-right fit',
                    sortable: true,
                    sortDirection: 'desc'
                },
                {
                    key: 'donor',
                    label: this.$t('app.donor'),
                    sortable: false
                },
                {
                    key: 'channel',
                    label: this.$t('app.channel'),
                    class: 'd-none d-sm-table-cell',
                    sortable: false
                },
                {
                    key: 'purpose',
                    label: this.$t('app.purpose'),
                    sortable: false
                },
                {
                    key: 'reference',
                    label: this.$t('app.reference'),
                    class: 'd-none d-sm-table-cell',
                    sortable: false
                },
                {
                    key: 'in_name_of',
                    label: this.$t('app.in_name_of'),
                    class: 'd-none d-sm-table-cell',
                    sortable: true
                },
                {
                    key: 'created_at',
                    label: this.$t('app.registered'),
                    class: 'd-none d-sm-table-cell fit',
                    sortable: true,
                    formatter: value => {
                        return moment(value).fromNow()
                    }
                },
                {
                    key: 'thanked',
                    label: this.$t('app.thanked'),
                    class: 'fit',
                    sortable: false
                }
            ],
            baseCurrency: null
        }
    },
    methods: {
        async list (params) {
            let data = await donationsApi.list(params)
            this.baseCurrency = data.meta.base_currency
            return data
        },
        numberFormat (value) {
            return numeral(value).format('0,0.00')
        }
    }
}
</script>
