<template>
    <div class="card mb-4">
        <div class="card-header">{{ title }}</div>
        <div class="card-body">
            <doughnut-chart
                :title="title"
                :url="url"
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
    },
    props: {
        title: {
            required: true,
            type: String
        },
        url: {
            required: true,
            type: String
        }
    }
}
</script>
