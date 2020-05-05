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
            <gender-distribution-widget />

            <!-- Age distribution -->
            <age-distribution-widget />

        </div>
        <div class="col-xl-6">

            <!-- Nationalities -->
            <nationality-distribution />

        </div>
    </div>
</template>

<script>
import numeral from 'numeral'
import BarChart from '@/components/BarChart'
import GenderDistributionWidget from '@/components/people/reporting/GenderDistributionWidget'
import AgeDistributionWidget from '@/components/people/reporting/AgeDistributionWidget'
import NationalityDistribution from '@/components/people/reporting/NationalityDistribution'
export default {
    components: {
        BarChart,
        GenderDistributionWidget,
        AgeDistributionWidget,
        NationalityDistribution
    },
    props: {
        people: {
            required: true
        },
        fromDate: {
            required: true
        },
        toDate: {
            required: true
        }
    },
    methods: {
        numberFormat (value) {
            return numeral(value).format('0,0')
        }
    }
}
</script>
