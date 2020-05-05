<template>
    <div class="card mb-4">
        <div class="card-header">{{ $t('people.gender') }}</div>
        <div class="card-body">
            <doughnut-chart
                :title="$t('people.gender')"
                :url="route('api.people.reporting.genderDistribution')"
                hide-legend
                :height="300"
                class="mb-2">
            </doughnut-chart>
        </div>
    </div>
</template>

<script>
import DoughnutChart from '@/components/charts/DoughnutChart'
export default {
    components: {
        DoughnutChart
    }
}
</script>
