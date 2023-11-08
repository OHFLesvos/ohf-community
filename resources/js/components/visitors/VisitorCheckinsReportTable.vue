<template>
    <div>
        <DateRangeSelect v-model="dateRange" />
        <div class="mb-2">
            <b-button v-for="preset in presets"
                :key="preset.text"
                size="sm"
                class="mr-2"
                :variant="preset.range.from == dateRange.from && preset.range.to == dateRange.to && preset.range.granularity == dateRange.granularity ? 'secondary' : 'outline-secondary'"
                @click="dateRange = preset.range">
                {{ preset.text }}
            </b-button>
        </div>
        <BaseTable
            ref="table"
            id="visitor-daily-checkins"
            :apiMethod="fetchData"
            :fields="fields"
            defaultSortBy="checkin_date"
            :defaultSortDesc="true"
            noFilter
        >
        <template #custom-foot="data">
            <b-tr>
                <b-th><em>{{ $t('Total') }}</em></b-th>
                <b-th class="text-right">
                    {{ data.items.reduce((a,b) => a + b.checkin_count, 0) }}
                </b-th>
            </b-tr>
        </template>
        </BaseTable>
    </div>
</template>

<script>
import DateRangeSelect from "@/components/common/DateRangeSelect.vue";
import BaseTable from "@/components/table/BaseTable.vue";

import moment from 'moment/min/moment-with-locales';
import visitorsApi from "@/api/visitors";
import { mapState } from "vuex";

export default {
    components: {
        DateRangeSelect,
        BaseTable
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
                    text: 'Last 7 days',
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
                    text: 'Current week',
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
                    text: 'Last 30 days',
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
                    text: 'Current month',
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
                    text: 'Last 3 months',
                    range: {
                        from: moment()
                            .subtract(3, "months")
                            .startOf('month')
                            .format(moment.HTML5_FMT.DATE),
                        to: moment()
                            .subtract(1, "months")
                            .endOf('month')
                            .format(moment.HTML5_FMT.DATE),
                        granularity: "months",
                    }
                },
                {
                    text: 'Current year',
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
            visitorsApi
        };
    },
    computed: {
        ...mapState(["settings"]),
        fields() {
            return [
            this.dateRange.granularity == 'days' ? {
                    key: "checkin_date",
                    label: this.$t("Date"),
                    formatter: this.dateWeekdayFormat,
                } : null,
                this.dateRange.granularity == 'weeks' ? {
                    key: "checkin_week",
                    label: this.$t("Week"),
                    formatter: v => `${moment(v).format('w')} (${moment(v).startOf('week').format('LL')} - ${moment(v).endOf('week').format('LL')})`,
                } : null,
                this.dateRange.granularity == 'months' ? {
                    key: "checkin_month",
                    label: this.$t("Month"),
                    formatter: v => moment(v).format('MMMM YYYY'),
                } : null,
                this.dateRange.granularity == 'years' ? {
                    key: "checkin_year",
                    label: this.$t("Year"),
                } : null,
                {
                    key: "checkin_count",
                    label: this.$t("Total"),
                    class: "text-right",
                },
            ];
        },
    },
    watch: {
        dateRange() {
            this.refresh();
        }
    },
    methods: {
        async fetchData() {
            if (this.dateRange.granularity == 'weeks') {
                return await visitorsApi.checkinsPerWeek(this.dateRange.from, this.dateRange.to);
            }
            if (this.dateRange.granularity == 'months') {
                return await visitorsApi.checkinsPerMonth(this.dateRange.from, this.dateRange.to);
            }
            if (this.dateRange.granularity == 'years') {
                return await visitorsApi.checkinsPerYear(this.dateRange.from, this.dateRange.to);
            }
            return await visitorsApi.checkinsPerDay(this.dateRange.from, this.dateRange.to);
        },
        refresh() {
            this.$refs.table.refresh();
        }
    },
};
</script>
