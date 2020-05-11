<template>
    <div>
        <div
            v-if="coupons"
            class="table-responsive"
        >
            <table class="table table-sm table-bordered table-striped table-hover mb-4">
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-right">{{ $t('app.daily_average') }}</th>
                        <th class="text-right">{{ $t('app.highest') }}</th>
                        <th class="text-right">{{ $t('app.last_month') }}</th>
                        <th class="text-right">{{ $t('app.this_month') }}</th>
                        <th class="text-right">{{ $t('app.last_week') }}</th>
                        <th class="text-right">{{ $t('app.this_week') }}</th>
                        <th class="text-right">{{ $t('app.today') }}</th>
                    </tr>
                </thead>
                <tbody>
                        <tr
                            v-for="v in coupons"
                            :key="v.coupon.id"
                        >
                            <td>{{ v.coupon.name }}</td>
                            <td class="text-right">{{ v.avg_sum }}</td>
                            <td class="text-right">
                                <template v-if="v.highest_sum">
                                    {{ v.highest_sum.sum }}
                                    <small class="text-muted">{{ v.highest_sum.date }}</small>
                                </template>
                            </td>
                            <td class="text-right">{{ v.last_month_sum }}</td>
                            <td class="text-right">{{ v.this_month_sum }}</td>
                            <td class="text-right">{{ v.last_week_sum }}</td>
                            <td class="text-right">{{ v.this_week_sum }}</td>
                            <td class="text-right">{{ v.today_sum }}</td>
                        </tr>
                </tbody>
            </table>
        </div>
        <p v-else>
            {{ $t('app.loading') }}
        </p>

        <div class="mb-3">
            <bar-chart
                v-for="v in coupons"
                :key="v.coupon.id"
                :title="$t('people.num_x_handed_out_per_day', { name: v.coupon.name })"
                :x-label="$t('app.date')"
                :y-label="$t('app.quantity')"
                :url="`${route('api.bank.reporting.couponsHandedOutPerDay', [ v.coupon.id ])}?from=${from}&to=${to}`"
                :height="300"
                class="mb-2">
            </bar-chart>
        </div>

    </div>
</template>

<script>
import bankApi from '@/api/bank'
import moment from 'moment'
import BarChart from '@/components/charts/BarChart'
export default {
    components: {
        BarChart
    },
    data () {
        return {
            coupons: null,
            from: moment().subtract(1, 'months').format(moment.HTML5_FMT.DATE),
            to: moment().format(moment.HTML5_FMT.DATE)
        }
    },
    mounted () {
        this.loadData()
    },
    methods: {
        loadData () {
            bankApi.fetchWithdrawalReportData()
                .then(data => this.coupons = data)
        }
    }
}
</script>
