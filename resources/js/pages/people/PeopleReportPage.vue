<template>
    <div class="row mb-0 mb-sm-2">
        <div class="col-xl-6">

            <!-- Registrations -->
            <div class="card mb-4">
                <div class="card-header">{{ $t('people.registrations') }}</div>
                <div class="card-body">

                    <!-- Important figues -->
                    <div
                        v-for="(row, idx) in people"
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

                    <!-- Registrations per day -->
                    <bar-chart
                        :title="$t('app.new_registrations_per_day')"
                        :x-label="$t('app.date')"
                        :y-label="$t('app.quantity')"
                        :url="route('api.people.reporting.registrationsPerDay', {from: fromDate, to: toDate})"
                        :height="350"
                        class="mb-0">
                    </bar-chart>
                </div>
            </div>

            <!-- Gender -->
            <div class="card mb-4">
                <div class="card-header">{{ $t('people.gender') }}</div>
                <div class="card-body">
                    <doughnut-chart
                        :title="$t('people.gender')"
                        :url="route('api.people.reporting.genderDistribution')"
                        hide-legend
                        :height="300"
                        class="mb-2">
                    </doughnut-chart>
                </div>
            </div>

            <!-- Age distribution -->
            <div v-if="ageDistributionTotal > 0" class="card mb-4">
                <div class="card-header">{{ $t('people.age_distribution') }}</div>
                <div class="card-body">
                    <doughnut-chart
                        :title="$t('people.age_distribution')"
                        :url="route('api.people.reporting.ageDistribution')"
                        :height="300"
                        class="mb-2">
                    </doughnut-chart>
                    <table class="table table-sm mb-0 colorize">
                        <tr
                            v-for="(v, k) in ageDistribution"
                            :key="k"
                        >
                            <td
                                class="colorize-background"
                                style="width: 2em"
                            >&nbsp;</td>
                            <td>{{ k }}</td>
                            <td class="text-right">{{ percentValue(v, ageDistributionTotal) }}%</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Cards -->
            <div class="card mb-4">
                <div class="card-header">{{ $t('app.cards') }}</div>
                <div
                    v-for="(row, idx) in cards"
                    :key="idx"
                    class="card-body"
                >
                    <div class="row mb-4 align-items-center">
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
                </div>
            </div>

        </div>
        <div class="col-xl-6">

            <!-- Nationalities -->
            <div
                v-if="Object.keys(nationalities).length > 0"
                class="card mb-4"
            >
                <div class="card-header">{{ $t('people.nationalities') }}</div>
                <div class="card-body">
                    <doughnut-chart
                        :title="$t('people.nationalities')"
                        :url="route('api.people.reporting.nationalities')"
                        :height="300"
                        class="mb-2">
                    </doughnut-chart>
                </div>
                <div class="table-responsive mb-0">
                    <table class="table table-sm my-0 colorize">
                        <tr
                            v-for="(v, nationality) in nationalities"
                            :key="nationality"
                        >
                            <td class="fit">
                                {{ nationality }}
                            </td>
                            <td class="align-middle d-none d-sm-table-cell">
                                <b-progress
                                    :value="v"
                                    :max="nationalityTotal"
                                    :show-value="false"
                                    variant="secondary"
                                />
                            </td>
                            <td class="fit text-right">
                                {{ percentValue(v, nationalityTotal) }}%
                            </td>
                            <td class="fit text-right">
                                {{ numberFormat(v) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import numeral from 'numeral'
import BarChart from '@/components/BarChart'
import DoughnutChart from '@/components/charts/DoughnutChart'
import { roundWithDecimals } from '@/utils'
export default {
    components: {
        BarChart,
        DoughnutChart
    },
    props: {
        people: {
            required: true
        },
        ageDistribution: {
            required: true
        },
        cards: {
            required: true
        },
        nationalities: {
            required: true
        },
        fromDate: {
            required: true
        },
        toDate: {
            required: true
        }
    },
    computed: {
        ageDistributionTotal () {
            return Object.values(this.ageDistribution).reduce((a,b) => a + b, 0)
        },
        nationalityTotal () {
            return Object.values(this.nationalities).reduce((a,b) => a + b, 0)
        }
    },
    methods: {
        numberFormat (value) {
            return numeral(value).format('0,0')
        },
        percentValue (val, total) {
            return roundWithDecimals(val / total * 100, 1)
        }
    }
}
</script>
