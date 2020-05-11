<template>
    <!-- TODO check length && Object.keys(data).length > 0 -->
    <doughnut-chart-table-distribution-widget
        v-if="data"
        :data="data"
        :title="$t('people.nationalities')"
        :url="route('api.people.reporting.nationalities')"
    />
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import peopleApi from '@/api/people'
import DoughnutChartTableDistributionWidget from '@/components/reporting/DoughnutChartTableDistributionWidget'
export default {
    components: {
        DoughnutChartTableDistributionWidget
    },
    data () {
        return {
            data: null
        }
    },
    async created () {
        this.data = await peopleApi.fetchNationalityDistribution()
    }
}
</script>
