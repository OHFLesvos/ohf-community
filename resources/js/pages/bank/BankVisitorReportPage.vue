<template>
    <div>
        <div class="row mb-0 mb-sm-2">
            <div class="col-xl-6">

                <!-- Important figures -->
                <template v-if="visitors">
                    <div
                        v-for="(row, idx) in visitors"
                        :key="idx"
                        class="row mb-4 align-items-center"
                    >
                        <div
                            v-for="(value, label) in row"
                            :key="label"
                            class="col"
                        >
                            <div class="row align-items-center">
                                <div class="col text-secondary">{{ label }}:</div>
                                <div class="col display-4">{{ value }}</div>
                            </div>
                        </div>
                        <div class="w-100 d-block d-sm-none"></div>
                    </div>
                </template>
                <p v-else>
                    {{ $t('app.loading') }}
                </p>

                <!-- Average visitors per day of week -->
                <bar-chart
                    title="Average visitors per day of week"
                    :x-label="$t('app.weekday')"
                    :y-label="$t('app.quantity')"
                    :url="route('api.bank.reporting.avgVisitorsPerDayOfWeek')"
                    :height="270">
                </bar-chart>

            </div>
            <div class="col-xl-6">

                <!-- Visitors per day -->
                <bar-chart
                    title="Visitors per day"
                    :x-label="$t('app.day')"
                    :y-label="$t('app.quantity')"
                    :url="route('api.bank.reporting.visitorsPerDay')"
                    :height="270">
                </bar-chart>

                <!-- Visitors per week -->
                <bar-chart
                    title="Visitors per week"
                    :x-label="$t('app.week')"
                    :y-label="$t('app.quantity')"
                    :url="route('api.bank.reporting.visitorsPerWeek')"
                    :height="270">
                </bar-chart>

                <!-- Visitors per month -->
                <bar-chart
                    title="Visitors per month"
                    :x-label="$t('app.month')"
                    :y-label="$t('app.quantity')"
                    :url="route('api.bank.reporting.visitorsPerMonth')"
                    :height="270">
                </bar-chart>

                <!-- Visitors per year -->
                <bar-chart
                    title="Visitors per year"
                    :x-label="$t('app.year')"
                    :y-label="$t('app.quantity')"
                    :url="route('api.bank.reporting.visitorsPerYear')"
                    :height="270">
                </bar-chart>

            </div>
        </div>

        <p><em><small>Visitor data is based on check-ins at the Bank.</small></em></p>
    </div>
</template>

<script>
import axios from '@/plugins/axios'
import BarChart from '@/components/charts/BarChart'
export default {
    components: {
        BarChart
    },
    data () {
        return {
            visitors: null
        }
    },
    mounted () {
        this.loadData()
    },
    methods: {
        loadData () {
            const url =this.route('api.bank.reporting.visitors')
            axios.get(url)
                .then(res => {
                    this.visitors = res.data
                })
        }
    }
}
</script>
