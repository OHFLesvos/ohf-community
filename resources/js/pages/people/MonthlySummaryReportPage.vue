<template>
    <div v-if="data">

        <div class="row">
            <div class="col-sm">
                <h3 class="mb-4">{{ currentMonthName }}</h3>
            </div>
            <div
                v-if="data.months && Object.keys(data.months).length > 0"
                class="col-sm-auto"
            >
                <b-form-select v-model="selectedMonth" :options="monthOptions"/>
            </div>
        </div>

        <div class="row">
            <div
                v-for="(item, idx) in computedData"
                :key="idx"
                class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3"
            >
                <div class="card text-dark bg-light">
                    <div class="card-body text-right">
                        <big class="display-4">{{ numberFormat(item.value) }}</big>
                        <template v-if="item.percent">
                            &nbsp;
                            <small :class="{ 'text-success': item.percent > 0, 'text-danger': item.percent < 0 }">
                                {{ Math.abs(item.percent) }}%
                                <font-awesome-icon v-if="item.percent > 0" icon="caret-up" />
                                <font-awesome-icon v-if="item.percent < 0" icon="caret-down" />
                            </small>
                        </template>
                        <br>
                        <small class="text-uppercase">{{ item.label }}</small>
                        <template v-if="item.ytd">
                            <br><small class="text-muted" title="Year-to-date">
                                {{ numberFormat(item.ytd) }} (YTD)
                            </small>
                        </template>
                    </div>
                    <ul
                        v-if="item.list"
                        class="list-group list-group-flush"
                    >
                        <li
                            v-for="(li, idx) in item.list"
                            :key="idx"
                            class="list-group-item d-flex justify-content-between"
                        >
                            <span>{{ li.label }}</span>
                            <span class="pull-right">
                                {{ numberFormat(li.value) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import peopleApi from '@/api/people'
import moment from 'moment'
import numberFormatMixin from '@/mixins/numberFormatMixin'
export default {
    mixins: [
        numberFormatMixin
    ],
    data () {
        return {
            data: null,
            selectedMonth: null,
        }
    },
    computed: {
        currentMonthName () {
            return moment(this.data.monthDate).format('MMMM YYYY')
        },
        monthOptions () {
            return Object.entries(this.data.months).map(e => {
                return {
                    value: e[0],
                    text: e[1]
                }
            })
        },
        computedData () {
            let current_average_visitors_per_day = this.data.current_days_active > 0 ? Math.round(this.data.current_total_visitors / this.data.current_days_active) : 0;
            let previous_average_visitors_per_day = this.data.previous_days_active > 0 ? Math.round(this.data.previous_total_visitors / this.data.previous_days_active) : 0;

            let current_average_registrations_per_day = this.data.current_days_active > 0 ? Math.round(this.data.current_new_registrations / this.data.current_days_active) : 0;
            let previous_average_registrations_per_day = this.data.previous_days_active > 0 ? Math.round(this.data.previous_new_registrations / this.data.previous_days_active) : 0;

            return [
                {
                    label: 'Coupons handed out',
                    value: this.data.current_coupons_handed_out,
                    percent: this.percentVal(this.data.previous_coupons_handed_out, this.data.current_coupons_handed_out),
                    list: this.data.current_coupon_types_handed_out,
                    ytd: this.data.year_coupons_handed_out,
                },
                {
                    label: 'Days active',
                    value: this.data.current_days_active,
                    percent: this.percentVal(this.data.previous_days_active, this.data.current_days_active),
                    ytd: this.data.year_days_active,
                },
                {
                    label: 'Unique visitors',
                    value: this.data.current_unique_visitors,
                    percent: this.percentVal(this.data.previous_unique_visitors, this.data.current_unique_visitors),
                    ytd: this.data.year_unique_visitors,
                },
                {
                    label: 'Total visitors',
                    value: this.data.current_total_visitors,
                    percent: this.percentVal(this.data.previous_total_visitors, this.data.current_total_visitors),
                    ytd: this.data.year_total_visitors,
                },
                {
                    label: 'Average visitors / day',
                    value: current_average_visitors_per_day,
                    percent: this.percentVal(previous_average_visitors_per_day, current_average_visitors_per_day),
                },
                {
                    label: 'New registrations',
                    value: this.data.current_new_registrations,
                    percent: this.percentVal(this.data.previous_new_registrations, this.data.current_new_registrations),
                    ytd: this.data.year_new_registrations,
                },
                {
                    label: 'Average registrations / day',
                    value: current_average_registrations_per_day,
                    percent: this.percentVal(previous_average_registrations_per_day, current_average_registrations_per_day),
                }
            ]
        }
    },
    watch: {
        selectedMonth (val, oldVal) {
            if (oldVal != null) {
                this.loadData()
            }
        }
    },
    mounted () {
        this.loadData()
    },
    methods: {
        async loadData () {
            let year, month
            if (this.selectedMonth != null) {
                const parts = this.selectedMonth.split('-');
                year = parts[0],
                month = parts[1]
            }
            try {
                let data = await peopleApi.fetchMonthlySummaryReportData(year, month)
                this.data = data
                this.selectedMonth = moment(this.data.monthDate).format('YYYY-MM')
            } catch (err) {
                alert(err)
            }
        },
        percentVal (prev, cur) {
            return prev != 0 ? Math.round(((cur - prev) / prev) * 100) : null
        }
    }
}
</script>
