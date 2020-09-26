<template>
    <b-container>
        <p class="text-right">
            <b-button
                variant="secondary"
                :to="{ name: 'visitors.listCurrent' }"
            >
                <font-awesome-icon icon="arrow-left"/>
                {{ $t('app.back') }}
            </b-button>
        </p>

        <h3>{{ $t('visitors.visitors_by_day') }}</h3>
        <b-table
            :items="dailyitemProvider"
            :fields="dailyFields"
            striped
            hover
            small
            bordered
            responsive
            :show-empty="true"
            :empty-text="$t('app.no_data_registered')"
            :caption="$t('app.showing_latest_n_active_days', { days: 30 })"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t('app.loading') }}</strong>
            </div>
        </b-table>

        <h3>{{ $t('visitors.visitors_by_month') }}</h3>
        <b-table
            :items="monthlyItemProvider"
            :fields="monthlyFields"
            striped
            hover
            small
            bordered
            responsive
            :show-empty="true"
            :empty-text="$t('app.no_data_registered')"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t('app.loading') }}</strong>
            </div>
        </b-table>
    </b-container>
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
                    label: this.$t('visitors.visitors'),
                    class: 'text-right'
                },
                {
                    key: 'participants',
                    label: this.$t('visitors.participants'),
                    class: 'text-right'
                },
                {
                    key: 'staff',
                    label: this.$t('visitors.volunteers_staff'),
                    class: 'text-right'
                },
                {
                    key: 'external',
                    label: this.$t('visitors.external_visitors'),
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
                    label: this.$t('visitors.visitors'),
                    class: 'text-right'
                },
                {
                    key: 'participants',
                    label: this.$t('visitors.participants'),
                    class: 'text-right'
                },
                {
                    key: 'staff',
                    label: this.$t('visitors.volunteers_staff'),
                    class: 'text-right'
                },
                {
                    key: 'external',
                    label: this.$t('visitors.external_visitors'),
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
