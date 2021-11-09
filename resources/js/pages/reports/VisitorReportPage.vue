<template>
    <b-container>
        <h3>{{ $t("Visitors by day") }}</h3>
        <b-table
            :items="dailyitemProvider"
            :fields="dailyFields"
            hover
            responsive
            :show-empty="true"
            :empty-text="$t('No data registered.')"
            :caption="$t('Showing the latest :days active days.', { days: 30 })"
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
    </b-container>
</template>

<script>
import moment from "moment";
import visitorsApi from "@/api/visitors";
import { mapState } from "vuex";
export default {
    title() {
        return this.$t("Report") + ": " + this.$t("Visitor check-ins");
    },
    data() {
        return {};
    },
    computed: {
        ...mapState(["settings"]),
        dailyFields() {
            return [
                {
                    key: "day",
                    label: this.$t("Date")
                },
                ...this.settings["visitors.purposes_of_visit"].map(k => ({
                    key: k,
                    label: k
                })),
                {
                    key: "total",
                    label: this.$t("Total"),
                    class: "text-right"
                }
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
                            month: item.month - 1
                        }).format("MMMM YYYY");
                    }
                },
                ...this.settings["visitors.purposes_of_visit"].map(k => ({
                    key: k,
                    label: k
                })),
                {
                    key: "total",
                    label: this.$t("Total"),
                    class: "text-right"
                }
            ];
        }
    },
    methods: {
        async dailyitemProvider(ctx) {
            let data = await visitorsApi.dailyVisitors();
            return data || [];
        },
        async monthlyItemProvider(ctx) {
            let data = await visitorsApi.monthlyVisitors();
            return data || [];
        }
    }
};
</script>
