<template>
    <div>
        <TimeBarChart
            :title="tableCaption"
            :key="tableCaption"
            :data="chartData"
            :date-from="date_start"
            :date-to="date_end"
            :granularity="granularity"
            class="mb-3 bg-white"
        />
        <BaseTable
            ref="table"
            id="visitor-checkins"
            :apiMethod="fetchData"
            :fields="fields"
            defaultSortBy="checkin_date_range"
            :defaultSortDesc="true"
            noFilter
            :caption="tableCaption"
            :responsive="false"
        >
            <template #custom-foot="data">
                <template v-if="data.items.length">
                    <b-tr>
                        <b-th>{{ $t('Total') }}</b-th>
                        <b-th class="text-right">
                            {{ data.items.reduce((a,b) => a + b.checkin_count, 0) }}
                        </b-th>
                    </b-tr>
                    <b-tr>
                        <b-th colspan="2" class="text-right">
                            <b-button size="sm" @click="copyToClipboard" variant="outline-secondary">
                            <template v-if="copied"><font-awesome-icon icon="check"/> {{ $t('Copied') }}</template>
                            <template v-else>{{ $t('Copy to clipboard') }}</template>
                        </b-button>
                        </b-th>
                    </b-tr>
                </template>
            </template>
        </BaseTable>
    </div>
</template>

<script>
import moment from 'moment/min/moment-with-locales';
import copy from 'copy-to-clipboard';

import visitorsApi from "@/api/visitors";

import BaseTable from "@/components/table/BaseTable.vue";
import TimeBarChart from "@/components/charts/TimeBarChart.vue";

export default {
    components: {
        BaseTable,
        TimeBarChart
    },
    props: {
        date_start: {
            required: true,
        },
        date_end: {
            required: true,
        },
        granularity: {
            required: true,
        },
        purpose: {
            required: false,
        },
    },
    data() {
        return {
            fetchedData: [],
            copied: false,
        };
    },
    computed: {
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
        tableCaption() {
            const args = {
                start_date: this.dateFormat(this.date_start),
                end_date: this.dateFormat(this.date_end),
                purpose: this.purpose,
            }
            if (this.granularity == 'weeks') {
                return this.purpose ? this.$t("Weekly visitor check-ins for {purpose} from {start_date} to {end_date}", args) : this.$t("Weekly visitor check-ins from {start_date} to {end_date}", args)
            }
            if (this.granularity == 'months') {
                return this.purpose ? this.$t("Monthly visitor check-ins for {purpose} from {start_date} to {end_date}", args) : this.$t("Monthly visitor check-ins from {start_date} to {end_date}", args)
            }
            if (this.granularity == 'years') {
                return this.purpose ? this.$t("Yearly visitor check-ins for {purpose} from {start_date} to {end_date}", args) : this.$t("Yearly visitor check-ins from {start_date} to {end_date}", args)
            }
            return this.purpose ? this.$t("Daily visitor check-ins for {purpose} from {start_date} to {end_date}", args) : this.$t("Daily visitor check-ins from {start_date} to {end_date}", args)
        },
        chartData() {
            let label;
            if (this.granularity == 'weeks') {
                label = this.$t('Visitors per week')
            } else if (this.granularity == 'months') {
                label = this.$t('Visitors per month')
            } else if (this.granularity == 'years') {
                label = this.$t('Visitors per year')
            } else {
                label = this.$t('Visitors per day')
            }
            return {
                labels: this.fetchedData.map(v => v.checkin_date_range),
                datasets: [
                    {
                        label: label,
                        unit: this.$t('Visitor Check-ins'),
                        data: this.fetchedData.map(v => v.checkin_count),
                    }
                ]
            }
        },
    },
    watch: {
        date_start() {
            this.refresh();
        },
        date_end() {
            this.refresh();
        },
        granularity() {
            this.refresh();
        },
        purpose() {
            this.refresh();
        }
    },
    methods: {
        async fetchData() {
            let data = await visitorsApi.visitorCheckins(this.date_start, this.date_end, this.granularity, this.purpose);
            this.fetchedData = data.data
            return data
        },
        refresh() {
            this.$refs.table.refresh();
        },
        dateRangeLabel() {
            if (this.granularity == 'weeks') {
                return this.$t("Week")
            }
            if (this.granularity == 'months') {
                return this.$t("Month")
            }
            if (this.granularity == 'years') {
                return this.$t("Year")
            }
            return this.$t("Date")
        },
        dateRangeFormatter(v) {
            if (this.granularity == 'weeks') {
                return `${moment(v).format('w')} (${moment(v).startOf('week').format('LL')} - ${moment(v).endOf('week').format('LL')})`
            }
            if (this.granularity == 'months') {
                return moment(v).format('MMMM YYYY')
            }
            if (this.granularity == 'years') {
                return v
            }
            return this.dateWeekdayFormat(v)
        },
        copyToClipboard() {
            const separator = '\t';
            const csvText = `${this.dateRangeLabel()}${separator}${this.$t("Total")}\n`
                + [...this.fetchedData].reverse().map(v => `${v.checkin_date_range}${separator}${v.checkin_count}`).join("\n")
                + `\n${this.$t('Total')}${separator}${this.fetchedData.reduce((a,b) => a + b.checkin_count, 0)}`

            copy(csvText, {
                format: 'text/plain',
                message: 'Press #{key} to copy',
            });
            this.copied = true
            setTimeout(() => this.copied = false, 3000)
        }
    },
};
</script>
