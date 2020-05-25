<template>
    <b-card
        :header="title"
        class="mb-4"
    >
        <doughnut-chart
            :title="title"
            :data="data"
            hide-legend
            :height="300"
            class="mb-2">
        </doughnut-chart>
    </b-card>
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
        data: {
            type: [Function, Object],
            required: true
        }
    }
}
</script>
