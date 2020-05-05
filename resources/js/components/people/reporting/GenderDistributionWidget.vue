<template>
    <!-- TODO length check -->
    <doughnut-chart-distribution-widget
        :title="$t('people.gender')"
        :url="route('api.people.reporting.genderDistribution')"
    />
</template>

<script>
import DoughnutChartDistributionWidget from '@/components/reporting/DoughnutChartDistributionWidget'
export default {
    components: {
        DoughnutChartDistributionWidget
    }
}
</script>
