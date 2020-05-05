<template>
    <div>
        <bar-chart
            :title="$t('people.age_distribution')"
            :x-label="$t('people.age')"
            :y-label="$t('app.quantity')"
            :url="route('api.people.helpers.ages')"
            :height="350"
            class="mb-2">
        </bar-chart>
        <div class="row mb-4">
            <div class="col-sm">
                <doughnut-chart
                    :title="$t('people.gender')"
                    :url="route('api.people.helpers.genders')"
                    hide-legend
                    :height="300"
                    class="mb-2">
                </doughnut-chart>
            </div>
            <div class="col-sm">
                <doughnut-chart
                    :title="$t('people.nationalities')"
                    :url="route('api.people.helpers.nationalities')"
                    :height="300"
                    class="mb-2">
                </doughnut-chart>
            </div>
        </div>
    </div>
</template>

<script>
import BarChart from '@/components/BarChart.vue';
import DoughnutChart from '@/components/charts/DoughnutChart.vue';
export default {
    components: {
        BarChart,
        DoughnutChart
    }
}
</script>
