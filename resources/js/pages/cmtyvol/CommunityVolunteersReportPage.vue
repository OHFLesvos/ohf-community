<template>
    <b-container fluid>
        <bar-chart
            :title="$t('Age distribution')"
            :x-label="$t('Age')"
            :y-label="$t('Quantity')"
            :data="api.ageDistribution"
            :height="350"
            class="mb-2"
        >
        </bar-chart>
        <div class="row mb-4">
            <div class="col-sm">
                <doughnut-chart
                    :title="$t('Gender')"
                    :data="api.genderDistribution"
                    hide-legend
                    :height="300"
                    class="mb-2"
                >
                </doughnut-chart>
            </div>
            <div class="col-sm">
                <doughnut-chart
                    :title="$t('Nationalities')"
                    :data="api.nationalityDistribution"
                    :height="300"
                    class="mb-2"
                >
                </doughnut-chart>
            </div>
        </div>
    </b-container>
</template>

<script>
import cmtyvolApi from "@/api/cmtyvol/cmtyvol";
import BarChart from "@/components/charts/BarChart.vue";
import DoughnutChart from "@/components/charts/DoughnutChart.vue";
export default {
    title() {
        return this.$t("Report") + ": " + this.$t("Community Volunteers");
    },
    components: {
        BarChart,
        DoughnutChart
    },
    data() {
        return {
            api: cmtyvolApi
        };
    }
};
</script>
