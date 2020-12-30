<template>
    <div>
        <div class="row mb-0 mb-sm-2">
            <div class="col-xl-6">

                <!-- Important figures -->
                <div v-if="visitors" class="bg-white rounded px-3 pt-3 pb-1 mb-4">
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
                </div>
                <p v-else>
                    {{ $t('app.loading') }}
                </p>

                <!-- Average visitors per day of week -->
                <bar-chart
                    title="Average visitors per day of week"
                    :x-label="$t('app.weekday')"
                    :y-label="$t('app.quantity')"
                    :data="api.fetchAvgVisitorsPerDayOfWeek"
                    :height="270"
                    class="bg-white p-2 mb-4 rounded"/>

                <!-- Visitors per day -->
                <bar-chart
                    title="Visitors per day"
                    :x-label="$t('app.day')"
                    :y-label="$t('app.quantity')"
                    :data="api.fetchVisitorsPerDay"
                    :height="270"
                    class="bg-white p-2 mb-4 rounded"/>

            </div>
            <div class="col-xl-6">

                <!-- Visitors per week -->
                <bar-chart
                    title="Visitors per week"
                    :x-label="$t('app.week')"
                    :y-label="$t('app.quantity')"
                    :data="api.fetchVisitorsPerWeek"
                    :height="270"
                    class="bg-white p-2 mb-4 rounded"/>

                <!-- Visitors per month -->
                <bar-chart
                    title="Visitors per month"
                    :x-label="$t('app.month')"
                    :y-label="$t('app.quantity')"
                    :data="api.fetchVisitorsPerMonth"
                    :height="270"
                    class="bg-white p-2 mb-4 rounded"/>

                <!-- Visitors per year -->
                <bar-chart
                    title="Visitors per year"
                    :x-label="$t('app.year')"
                    :y-label="$t('app.quantity')"
                    :data="api.fetchVisitorsPerYear"
                    :height="270"
                    class="bg-white p-2 mb-4 rounded"/>

            </div>
        </div>

        <p><em><small>Visitor data is based on check-ins at the Bank.</small></em></p>
    </div>
</template>

<script>
import bankApi from '@/api/bank'
import BarChart from '@/components/charts/BarChart'
export default {
    components: {
        BarChart
    },
    data () {
        return {
            visitors: null,
            api: bankApi
        }
    },
    mounted () {
        this.loadData()
    },
    methods: {
        async loadData () {
            this.visitors = await bankApi.fetchVisitorReportData()
        }
    }
}
</script>
