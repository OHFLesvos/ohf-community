<template>
    <!-- TODO check length && Object.keys(data).length > 0 -->
    <doughnut-chart-table-distribution-widget
        v-if="data"
        :data="data"
        :title="$t('people.nationalities')"
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
            url: this.route('api.people.reporting.nationalities')
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
