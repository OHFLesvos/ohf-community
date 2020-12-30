<template>
    <div>
        <b-table
            stacked="md"
            hover
            :fields="fields"
            :items="donations"
            show-empty
            :empty-text="$t('fundraising.no_donations_found')"
            class="shadow-md"
            tbody-class="bg-white"
            thead-class="bg-white"
        >
            <!-- Date / Link -->
            <template v-slot:cell(date)="data">
                <a
                    v-if="data.item.can_update"
                    href="javascript:;"
                    @click="$emit('select', data.item)"
                >
                    {{ data.value }}
                </a>
                <template v-else>
                    {{ data.value }}
                </template>
            </template>

            <!-- Amount / Currency -->
            <template v-slot:cell(exchange_amount)="data">
                <span
                    v-if="data.item.currency != baseCurrency"
                    class="mr-1"
                >
                    <small class="text-muted">
                        {{ data.item.currency }} {{ numberFormat(data.item.amount) }}
                    </small>
                </span>
                {{ baseCurrency }} {{ numberFormat(data.value) }}
            </template>

            <!-- Colgroup -->
            <template v-slot:table-colgroup="scope">
                <col
                    v-for="field in scope.fields"
                    :key="field.key"
                    :style="{ width: field.width }"
                >
            </template>

            <!-- Footer -->
            <template v-slot:custom-foot="data">
                <b-tr v-if="data.items.length > 0">
                    <b-th :colspan="1"></b-th>
                    <b-th class="text-right">
                        <u>{{ baseCurrency }} {{ numberFormat(totalAmount(data.items)) }}</u>
                    </b-th>
                    <b-th :colspan="5"></b-th>
                </b-tr>
            </template>
        </b-table>
    </div>
</template>

<script>
import moment from 'moment'
import numeral from 'numeral'
import { roundWithDecimals } from '@/utils'
export default {
    props: {
        donations: {
            type: Array,
            required: true
        },
        baseCurrency: {
            required: true,
            type: String
        }
    },
    data () {
        return {
            fields: [
                {
                    key: 'date',
                    label: this.$t('app.date'),
                    formatter: value => {
                        return moment(value).format('LL')
                    },
                    width: '10em'
                },
                {
                    key: 'exchange_amount',
                    label: this.$t('app.amount'),
                    class: 'text-md-right',
                    width: '12em'
                },
                {
                    key: 'channel',
                    label: this.$t('fundraising.channel'),
                    width: '10em'
                },
                {
                    key: 'purpose',
                    label: this.$t('fundraising.purpose')
                },
                {
                    key: 'reference',
                    label: this.$t('fundraising.reference'),
                    width: '12em'
                },
                {
                    key: 'in_name_of',
                    label: this.$t('fundraising.in_name_of'),
                    width: '10em'
                },
                {
                    key: 'created_at',
                    label: this.$t('app.registered'),
                    width: '12em',
                    formatter: value => {
                        return moment(value).format('LLL')
                    }
                },
                {
                    key: 'thanked',
                    label: this.$t('fundraising.thanked'),
                    width: '12em',
                    formatter: value => {
                        return value ? moment(value).format('LLL') : null
                    }
                }
            ]
        }
    },
    methods: {
        numberFormat (value) {
            return numeral(value).format('0,0.00')
        },
        totalAmount (items) {
            let sum = items.reduce((a,b) => a + parseFloat(b.exchange_amount), 0)
            return roundWithDecimals(sum, 2)
        }
    }
}
</script>
