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
                    }
                },
                {
                    key: 'channel',
                    label: this.$t('fundraising.channel'),
                    class: 'd-none d-sm-table-cell'
                },
                {
                    key: 'purpose',
                    label: this.$t('fundraising.purpose')
                },
                {
                    key: 'reference',
                    label: this.$t('fundraising.reference'),
                    class: 'd-none d-sm-table-cell'
                },
                {
                    key: 'in_name_of',
                    label: this.$t('fundraising.in_name_of'),
                    class: 'd-none d-sm-table-cell'
                },
                {
                    key: 'amount',
                    label: this.$t('app.amount'),
                    class: 'text-right'
                },
                {
                    key: 'thanked',
                    label: this.$t('fundraising.thanked'),
                    class: 'fit',
                    formatter: value => {
                        return value ? moment(value).format('LL') : null
                    }
                }
            ]
        }
    }
}
</script>
