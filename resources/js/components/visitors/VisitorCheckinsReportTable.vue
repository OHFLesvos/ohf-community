<template>
    <div>
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
                    text: this.$t('Last {months} months', { months: 30 }),
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
                start_date: this.dateFormat(this.dateRange.from),
                end_date: this.dateFormat(this.dateRange.to),
                purpose: this.purpose,
            }
            if (this.dateRange.granularity == 'weeks') {
                return this.purpose ? this.$t("Weekly visitor check-ins for {purpose} from {start_date} to {end_date}", args) : this.$t("Weekly visitor check-ins from {start_date} to {end_date}", args)
            }
            if (this.dateRange.granularity == 'months') {
                return this.purpose ? this.$t("Monthly visitor check-ins for {purpose} from {start_date} to {end_date}", args) : this.$t("Monthly visitor check-ins from {start_date} to {end_date}", args)
            }
            if (this.dateRange.granularity == 'years') {
                return this.purpose ? this.$t("Yearly visitor check-ins for {purpose} from {start_date} to {end_date}", args) : this.$t("Yearly visitor check-ins from {start_date} to {end_date}", args)
            }
            return this.purpose ? this.$t("Daily visitor check-ins for {purpose} from {start_date} to {end_date}", args) : this.$t("Daily visitor check-ins from {start_date} to {end_date}", args)
        }
    },
    watch: {
        dateRange() {
            this.refresh();
        },
        purpose() {
            this.refresh();
        }
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
            this.purposes.push(...await visitorsApi.listCheckinPurposes());
        },
        async fetchData() {
            return await visitorsApi.visitorCheckins(this.dateRange.from, this.dateRange.to, this.dateRange.granularity, this.purpose);
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
