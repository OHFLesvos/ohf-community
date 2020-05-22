<template>
    <div>
        <b-table
            responsive
            small
            hover
            striped
            :fields="fields"
            :items="donations"
            show-empty
            :empty-text="$t('fundraising.no_donations_found')"
        >
            <template v-slot:cell(date)="data">
                <a :href="route('fundraising.donations.edit', [donorId, data.item.id])">
                    {{ data.value }}
                </a>
            </template>

            <template v-slot:cell(amount)="data">
                {{ data.item.currency }} {{ data.value }}
                <span v-if="data.item.currency != baseCurrency">({{ baseCurrency }} {{ data.item.exchange_amount }})</span>
            </template>

            <template v-slot:table-colgroup="scope">
                <col
                    v-for="field in scope.fields"
                    :key="field.key"
                    :style="{ width: field.width }"
                >
            </template>

            <template v-slot:custom-foot="data">
                <b-tr>
                    <b-th :colspan="data.columns - 1"></b-th>
                    <b-th class="text-right">
                        <u>{{ baseCurrency }} {{ data.items.reduce((a,b) => a + parseFloat(b.exchange_amount), 0) }}</u>
                    </b-th>
                </b-tr>
            </template>

        </b-table>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        donorId: {
            type: Number,
            required: true
        },
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
                    key: 'channel',
                    label: this.$t('fundraising.channel'),
                    // class: 'd-none d-sm-table-cell',
                    width: '10em'
                },
                {
                    key: 'purpose',
                    label: this.$t('fundraising.purpose')
                },
                {
                    key: 'reference',
                    label: this.$t('fundraising.reference'),
                    // class: 'd-none d-sm-table-cell',
                    width: '12em'
                },
                {
                    key: 'in_name_of',
                    label: this.$t('fundraising.in_name_of'),
                    // class: 'd-none d-sm-table-cell',
                    width: '10em'
                },
                {
                    key: 'thanked',
                    label: this.$t('fundraising.thanked'),
                    class: 'fit',
                    formatter: value => {
                        return value ? moment(value).format('LL') : null
                    }
                },
                {
                    key: 'amount',
                    label: this.$t('app.amount'),
                    class: 'text-right',
                    width: '8em'
                }
            ]
        }
    }
}
</script>
