<template>
    <!-- TODO length check -->
    <doughnut-chart-table-distribution-widget
        v-if="data"
        :data="data"
        :title="$t('people.age_distribution')"
        :url="route('api.people.reporting.ageDistribution')"
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
        this.data = await peopleApi.fetchAgeDistribution()
    }
}
</script>
