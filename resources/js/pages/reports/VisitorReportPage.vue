<template>
    <div>
        <h3>{{ $t('app.visitors_by_day') }}</h3>
        <b-table
            :items="dailyitemProvider"
            :fields="dailyFields"
            hover
            responsive
            :show-empty="true"
            :empty-text="$t('app.no_data_registered')"
            :caption="$t('app.showing_latest_n_active_days', { days: 30 })"
            tbody-class="bg-white"
            thead-class="bg-white"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t('app.loading') }}</strong>
            </div>
        </b-table>

        <h3>{{ $t('app.visitors_by_month') }}</h3>
        <b-table
            :items="monthlyItemProvider"
            :fields="monthlyFields"
            hover
            responsive
            :show-empty="true"
            :empty-text="$t('app.no_data_registered')"
            class="bg-white"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t('app.loading') }}</strong>
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
                    label: this.$t('app.date')
                },
                {
                    key: 'visitors',
                    label: this.$t('app.visitors'),
                    class: 'text-right'
                },
                {
                    key: 'participants',
                    label: this.$t('app.participants'),
                    class: 'text-right'
                },
                {
                    key: 'staff',
                    label: this.$t('app.volunteers_staff'),
                    class: 'text-right'
                },
                {
                    key: 'external',
                    label: this.$t('app.external_visitors'),
                    class: 'text-right'
                },
                {
                    key: 'total',
                    label: this.$t('app.total'),
                    class: 'text-right'
                },
            ],
            monthlyFields: [
                {
                    key: 'date',
                    label: this.$t('app.date'),
                    formatter: (value, key, item) => {
                        return moment({ year: item.year, month: item.month - 1 }).format('MMMM YYYY')
                    }
                },
                {
                    key: 'visitors',
                    label: this.$t('app.visitors'),
                    class: 'text-right'
                },
                {
                    key: 'participants',
                    label: this.$t('app.participants'),
                    class: 'text-right'
                },
                {
                    key: 'staff',
                    label: this.$t('app.volunteers_staff'),
                    class: 'text-right'
                },
                {
                    key: 'external',
                    label: this.$t('app.external_visitors'),
                    class: 'text-right'
                },
                {
                    key: 'total',
                    label: this.$t('app.total'),
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
