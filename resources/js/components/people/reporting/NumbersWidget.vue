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
import axios from '@/plugins/axios'
import numeral from 'numeral'
import BarChart from '@/components/BarChart'
export default {
    components: {
        BarChart,
    },
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
    mounted () {
        this.loadData()
    },
    methods: {
        loadData () {
            const url =this.route('api.people.reporting.numbers', {
                from: this.dateRange.from,
                to: this.dateRange.to
            })
            axios.get(url)
                .then(res => {
                    this.data = res.data
                })
        },
        numberFormat (value) {
            return numeral(value).format('0,0')
        }
    }
}
</script>
