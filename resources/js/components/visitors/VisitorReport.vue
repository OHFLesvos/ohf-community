<template>
    <div>
        <h2>Visitor Report Date Selection</h2>

        <date-range-select v-model="dateRange" />

        <h3>Visitor Registrations by Nationality</h3>
        <bar-chart
            :title="'New Visitor Registrations by Nationality'"
            :x-label="'Nationality'"
            :y-label="'Visitor Count'"
            :data="visitorsApi.nationalityDistribution"
            :params="{
                'from': dateRange.from,
                'to': dateRange.to
            }"
            class="mb-3"
        />

        <h3>Visitor Registrations by Age Group</h3>
        <bar-chart
            :title="'Visitor Registrations by Age Group'"
            :x-label="'Age'"
            :y-label="'Number of Visitor Registrations'"
            :data="visitorsApi.ageDistribution"
            :params="{
                'from': dateRange.from,
                'to': dateRange.to
            }"
            class="mb-3"
        />

        <h3>Check-ins by Visitor</h3>
        <bar-chart
            :title="'Number of Check-ins by Visitor'"
            :x-label="'Visits'"
            :y-label="'Number of Visitors'"
            :data="visitorsApi.checkInsByVisitor"
            :params="{
                'from': dateRange.from,
                'to': dateRange.to
            }"
            class="mb-3"
        />
    </div>
</template>

<script>
import DateRangeSelect from "@/components/common/DateRangeSelect.vue";
import BarChart from "@/components/charts/BarChart.vue";
import TimeBarChart from "@/components/charts/TimeBarChart.vue";

import moment from "moment";
import visitorsApi from "@/api/visitors";

export default {
    components: {
        DateRangeSelect,
        BarChart,
        TimeBarChart,
    },
    data() {
        return {
            dateRange: {
                from: moment()
                    .subtract(3, "months")
                    .format(moment.HTML5_FMT.DATE),
                to: moment().format(moment.HTML5_FMT.DATE),
                granularity: "days",
            },
            visitorsApi
        };
    },
};
</script>
