<template>
    <doughnut-chart-table-distribution-widget
        :title="$t('people.age_distribution')"
        :data="api.fetchAgeDistribution"
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
