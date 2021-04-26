<template>
    <div>
        <h3>{{ $t('Visitors by day') }}</h3>
        <b-table
            :items="dailyitemProvider"
            :fields="dailyFields"
            hover
            responsive
            :show-empty="true"
            :empty-text="$t('No data registered.')"
            :caption="$t('Showing the latest :days active days.', { days: 30 })"
            tbody-class="bg-white"
            thead-class="bg-white"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t('Loading...') }}</strong>
            </div>
        </b-table>

        <h3>{{ $t('Visitors by month') }}</h3>
        <b-table
            :items="monthlyItemProvider"
            :fields="monthlyFields"
            hover
            responsive
            :show-empty="true"
            :empty-text="$t('No data registered.')"
            class="bg-white"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t('Loading...') }}</strong>
            </div>
        </b-table>
    </div>
</template>

<script>
import moment from 'moment'
import visitorsApi from '@/api/visitors'
export default {
    components: {
    },
    data () {
        return {
            dailyFields: [
                {
                    key: 'day',
                    label: this.$t('Date')
                },
                {
                    key: 'visitors',
                    label: this.$t('Visitors'),
                    class: 'text-right'
                },
                {
                    key: 'participants',
                    label: this.$t('Participants'),
                    class: 'text-right'
                },
                {
                    key: 'staff',
                    label: this.$t('Volunteers / Staff'),
                    class: 'text-right'
                },
                {
                    key: 'external',
                    label: this.$t('External visitors'),
                    class: 'text-right'
                },
                {
                    key: 'total',
                    label: this.$t('Total'),
                    class: 'text-right'
                },
            ],
            monthlyFields: [
                {
                    key: 'date',
                    label: this.$t('Date'),
                    formatter: (value, key, item) => {
                        return moment({ year: item.year, month: item.month - 1 }).format('MMMM YYYY')
                    }
                },
                {
                    key: 'visitors',
                    label: this.$t('Visitors'),
                    class: 'text-right'
                },
                {
                    key: 'participants',
                    label: this.$t('Participants'),
                    class: 'text-right'
                },
                {
                    key: 'staff',
                    label: this.$t('Volunteers / Staff'),
                    class: 'text-right'
                },
                {
                    key: 'external',
                    label: this.$t('External visitors'),
                    class: 'text-right'
                },
                {
                    key: 'total',
                    label: this.$t('Total'),
                    class: 'text-right'
                },
            ]
        }
    },
    methods: {
        async dailyitemProvider(ctx) {
            let data = await visitorsApi.dailyVisitors()
            return data || []
        },
        async monthlyItemProvider(ctx) {
            let data = await visitorsApi.monthlyVisitors()
            return data || []
        }
    }
}
</script>
