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
            id="visitor-checkins"
            :apiMethod="fetchData"
            :fields="fields"
            defaultSortBy="checkin_date_range"
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
                {
                    key: "checkin_date_range",
                    label: this.dateRangeLabel(),
                    formatter: this.dateRangeFormatter,
                },
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
            return await visitorsApi.visitorCheckins(this.dateRange.from, this.dateRange.to, this.dateRange.granularity);
        },
        refresh() {
            this.$refs.table.refresh();
        },
        dateRangeLabel() {
            if (this.dateRange.granularity == 'weeks') {
                return this.$t("Week")
            }
            if (this.dateRange.granularity == 'months') {
                return this.$t("Month")
            }
            if (this.dateRange.granularity == 'years') {
                return this.$t("Year")
            }
            return this.$t("Date")
        },
        dateRangeFormatter(v) {
            if (this.dateRange.granularity == 'weeks') {
                return `${moment(v).format('w')} (${moment(v).startOf('week').format('LL')} - ${moment(v).endOf('week').format('LL')})`
            }
            if (this.dateRange.granularity == 'months') {
                return moment(v).format('MMMM YYYY')
            }
            if (this.dateRange.granularity == 'years') {
                return v
            }
            return this.dateWeekdayFormat(v)
        },
    },
};
</script>
