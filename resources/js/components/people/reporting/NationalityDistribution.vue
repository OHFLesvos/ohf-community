<template>
    <!-- TODO check length && Object.keys(data).length > 0 -->
    <doughnut-chart-table-distribution-widget
        :title="$t('people.nationalities')"
        :data-provider="api.fetchNationalityDistribution"
    />
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
            api: peopleApi
        }
    }
}
</script>
