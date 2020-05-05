<template>
    <!-- TODO length check -->
    <doughnut-chart-table-distribution-widget
        v-if="data"
        :data="data"
        :title="$t('people.age_distribution')"
        :url="url"
    />
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import axios from '@/plugins/axios'
import DoughnutChartTableDistributionWidget from '@/components/reporting/DoughnutChartTableDistributionWidget'
export default {
    components: {
        DoughnutChartTableDistributionWidget
    },
    data () {
        return {
            data: null,
            url: this.route('api.people.reporting.ageDistribution')
        }
    },
    mounted () {
        axios.get(this.url)
            .then(res => {
                this.data = res.data
            })
    }
}
</script>
