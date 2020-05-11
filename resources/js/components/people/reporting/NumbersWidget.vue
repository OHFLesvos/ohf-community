<template>
    <div class="card mb-4">
        <div class="card-header">{{ $t('people.registrations') }}</div>
        <div class="card-body">

            <!-- Important figues -->
            <template v-if="data">
                <div
                    v-for="(row, idx) in data"
                    :key="idx"
                    class="row mb-4 align-items-center"
                >
                    <div
                        v-for="(v, k) in row"
                        :key="k"
                        class="col"
                    >
                        <div class="row align-items-center">
                            <div class="col text-secondary">{{ k }}:</div>
                            <div class="col display-4">{{ numberFormat(v) }}</div>
                        </div>
                    </div>
                    <div class="w-100 d-block d-sm-none"></div>
                </div>
            </template>
            <p v-else>
                {{ $t('app.loading') }}
            </p>

            <!-- Registrations per day -->
            <bar-chart
                :title="$t('app.new_registrations_per_day')"
                :x-label="$t('app.date')"
                :y-label="$t('app.quantity')"
                :url="route('api.people.reporting.registrationsPerDay', {
                    from: dateRange.from,
                    to: dateRange.to
                })"
                :height="350"
                class="mb-0">
            </bar-chart>
        </div>
    </div>
</template>

<script>
import peopleApi from '@/api/people'
import BarChart from '@/components/charts/BarChart'
import numberFormatMixin from '@/mixins/numberFormatMixin'
export default {
    components: {
        BarChart,
    },
    mixins: [
        numberFormatMixin
    ],
    props: {
        dateRange: {
            required: true,
            type: Object
        }
    },
    data () {
        return {
            data: null
        }
    },
    watch: {
        dateRange () {
            this.loadData()
        }
    },
    created () {
        this.loadData()
    },
    methods: {
        async loadData () {
            this.data = await peopleApi.fetchNumberReportData(this.dateRange.from, this.dateRange.to)
        }
    }
}
</script>
