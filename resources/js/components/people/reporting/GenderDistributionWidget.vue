<template>
    <!-- TODO length check -->
    <doughnut-chart-distribution-widget
        :title="$t('people.gender')"
        :data-provider="api.fetchGenderDistribution"
    />
</template>

<script>
import peopleApi from '@/api/people'
import DoughnutChartDistributionWidget from '@/components/reporting/DoughnutChartDistributionWidget'
export default {
    components: {
        DoughnutChartDistributionWidget
    },
    data () {
        return {
            api: peopleApi
        }
    }
}
</script>
