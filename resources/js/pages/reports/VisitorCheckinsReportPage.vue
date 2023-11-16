<template>
    <b-container>
        <DateRangeSelect v-model="dateRange" />
        <div class="mb-2">
            <b-button v-for="(preset, idx) in presets"
                :key="idx"
                size="sm"
                class="mr-2"
                :variant="preset.range.from == dateRange.from && preset.range.to == dateRange.to && preset.range.granularity == dateRange.granularity ? 'secondary' : 'outline-secondary'"
                @click="dateRange = preset.range">
                {{ preset.text }}
            </b-button>
        </div>

        <div class="mb-2">
            <b-input-group :prepend="$t('Purpose of visit')">
                <b-form-select
                    v-model="purpose"
                    :options="purposes"
                />
            </b-input-group>
        </div>

        <VisitorCheckinsReportTable
            :date_start="dateRange.from"
            :date_end="dateRange.to"
            :granularity="dateRange.granularity"
            :purpose="purpose"
        />

        <VisitorGenderDistributionChart
            v-if="purpose == null"
            :date_start="dateRange.from"
            :date_end="dateRange.to"
        />

        <VisitorCheckinPurposeChart
            v-if="purpose == null"
            :date_start="dateRange.from"
            :date_end="dateRange.to"
        />

    </b-container>
</template>

<script>
import moment from 'moment/min/moment-with-locales';
import { mapState } from "vuex";

import DateRangeSelect from "@/components/common/DateRangeSelect.vue";
import VisitorCheckinsReportTable from "@/components/visitors/VisitorCheckinsReportTable.vue";
import VisitorCheckinPurposeChart from "@/components/visitors/VisitorCheckinPurposeChart.vue";
import VisitorGenderDistributionChart from "@/components/visitors/VisitorGenderDistributionChart.vue";

export default {
    title() {
        return this.$t("Report") + ": " + this.$t("Visitor check-ins");
    },
    components: {
        DateRangeSelect,
        VisitorCheckinsReportTable,
        VisitorCheckinPurposeChart,
        VisitorGenderDistributionChart
    },
    data() {
        return {
            dateRange: {
                from: moment()
                    .subtract(7, "days")
                    .format(moment.HTML5_FMT.DATE),
                to: moment().format(moment.HTML5_FMT.DATE),
                granularity: "days",
            },
            presets: [
                {
                    text: this.$t('Last {days} days', { days: 7 }),
                    range: {
                        from: moment()
                            .subtract(7, "days")
                            .format(moment.HTML5_FMT.DATE),
                        to: moment()
                            .format(moment.HTML5_FMT.DATE),
                        granularity: "days",
                    }
                },
                {
                    text: this.$t('Current week'),
                    range: {
                        from: moment()
                            .startOf('week')
                            .format(moment.HTML5_FMT.DATE),
                        to: moment()
                            .format(moment.HTML5_FMT.DATE),
                        granularity: "days",
                    }
                },
                {
                    text: this.$t('Last {days} days', { days: 30 }),
                    range: {
                        from: moment()
                            .subtract(30, "days")
                            .format(moment.HTML5_FMT.DATE),
                        to: moment()
                            .format(moment.HTML5_FMT.DATE),
                        granularity: "days",
                    }
                },
                {
                    text: this.$t('Current month'),
                    range: {
                        from: moment()
                            .startOf('month')
                            .format(moment.HTML5_FMT.DATE),
                        to: moment()
                            .format(moment.HTML5_FMT.DATE),
                        granularity: "days",
                    }
                },
                {
                    text: this.$t('Last {months} months', { months: 3 }),
                    range: {
                        from: moment()
                            .subtract(3, "months")
                            .startOf('month')
                            .format(moment.HTML5_FMT.DATE),
                        to: moment()
                            .subtract(1, "months")
                            .endOf('month')
                            .format(moment.HTML5_FMT.DATE),
                        granularity: "weeks",
                    }
                },
                {
                    text: this.$t('Current year'),
                    range: {
                        from: moment()
                            .startOf('year')
                            .format(moment.HTML5_FMT.DATE),
                        to: moment()
                            .format(moment.HTML5_FMT.DATE),
                        granularity: "months",
                    }
                }
            ],
            purpose: null,
            purposes: [],
        };
    },
    computed: {
        ...mapState(["settings"]),
    },
    async created() {
        this.fetchPurposes()
    },
    methods: {
        async fetchPurposes() {
            this.purposes = [{
                text: this.$t('Any'),
                value: null
            }]
            this.purposes.push(...this.settings['visitors.purposes_of_visit']);
        },
    }
};
</script>
