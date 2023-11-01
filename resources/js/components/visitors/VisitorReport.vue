<template>
    <div>
        <h3>{{ $t("Visitors by day") }}</h3>
        <b-table
            :items="dailyItemProvider"
            :fields="dailyFields"
            hover
            responsive
            :show-empty="true"
            :empty-text="$t('No data registered.')"
            :caption="$t('Showing the latest {days} active days.', { days: numberOfDays })"
            tbody-class="bg-white"
            thead-class="bg-white"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t("Loading...") }}</strong>
            </div>
        </b-table>

        <h3>{{ $t("Visitors by month") }}</h3>
        <b-table
            :items="monthlyItemProvider"
            :fields="monthlyFields"
            hover
            responsive
            :show-empty="true"
            :empty-text="$t('No data registered.')"
            class="bg-white"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t("Loading...") }}</strong>
            </div>
        </b-table>

        <h2>Visitor Report Date Selection</h2>

        <date-range-select v-model="dateRange" />

        <h3>Visitor Registrations over Time</h3>
        <time-bar-chart
            :title="'Visitors Registrations over Time'"
            :data="visitorsApi.dailyRegistrations"
            :date-from="dateRange.from"
            :date-to="dateRange.to"
            :granularity="dateRange.granularity"
            class="mb-3"
        />

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

        <h3>Check-ins by Purpose</h3>
        <time-bar-chart
            :title="'Number of Check-ins by Purpose'"
            :data="visitorsApi.checkInsByPurpose"
            :date-from="dateRange.from"
            :date-to="dateRange.to"
            :granularity="dateRange.granularity"
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
const numberOfDays = 10;
import DateRangeSelect from "@/components/common/DateRangeSelect.vue";
import BarChart from "@/components/charts/BarChart.vue";
import TimeBarChart from "@/components/charts/TimeBarChart.vue";

import moment from "moment";
import visitorsApi from "@/api/visitors";
import { mapState } from "vuex";
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
    computed: {
        ...mapState(["settings"]),
        dailyFields() {
            return [
                {
                    key: "day",
                    label: this.$t("Date"),
                },
                ...this.settings["visitors.purposes_of_visit"].map((k) => ({
                    key: k,
                    label: k,
                })),
                {
                    key: "total",
                    label: this.$t("Total"),
                    class: "text-right",
                },
            ];
        },
        monthlyFields() {
            return [
                {
                    key: "date",
                    label: this.$t("Date"),
                    formatter: (value, key, item) => {
                        return moment({
                            year: item.year,
                            month: item.month - 1,
                        }).format("MMMM YYYY");
                    },
                },
                ...this.settings["visitors.purposes_of_visit"].map((k) => ({
                    key: k,
                    label: k,
                })),
                {
                    key: "total",
                    label: this.$t("Total"),
                    class: "text-right",
                },
            ];
        },
        numberOfDays: () => numberOfDays
    },
    methods: {
        async dailyItemProvider() {
            let data = await visitorsApi.dailyVisitors({
                days: numberOfDays,
            });
            return data || [];
        },
        async monthlyItemProvider() {
            let data = await visitorsApi.monthlyVisitors();
            return data || [];
        },
    },
};
</script>
