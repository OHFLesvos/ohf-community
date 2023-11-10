<template>
    <div>
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
import moment from 'moment/min/moment-with-locales';

import visitorsApi from "@/api/visitors";

import BaseTable from "@/components/table/BaseTable.vue";

export default {
    components: {
        BaseTable
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
        }
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
            return await visitorsApi.visitorCheckins(this.date_start, this.date_end, this.granularity, this.purpose);
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
    },
};
</script>
