<template>
    <DoughnutChartTableDistributionWidget v-if="fetchedData"
        :title="$t('Nationalities')"
        :data="fetchedData"
    />
</template>

<script>
import DoughnutChartTableDistributionWidget from "@/components/reporting/DoughnutChartTableDistributionWidget.vue";

import visitorsApi from "@/api/visitors";

export default {
    components: {
        DoughnutChartTableDistributionWidget,
    },
    props: {
        date_start: {
            required: false,
        },
        date_end: {
            required: false,
        },
    },
    data() {
        return {
            fetchedData: null
        }
    },
    watch: {
        date_start() {
            this.fetchData()
        },
        date_end() {
            this.fetchData()
        },
    },
    async created()
    {
        this.fetchData()
    },
    methods: {
        async fetchData()
        {
            this.fetchedData = await visitorsApi.nationalityDistribution(this.date_start, this.date_end)
        }
    }
}
</script>
