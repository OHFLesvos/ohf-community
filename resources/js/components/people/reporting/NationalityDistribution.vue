<template>
    <doughnut-chart-table-distribution-widget
        :title="$t('people.nationalities')"
        :data="api.fetchNationalityDistribution"
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
