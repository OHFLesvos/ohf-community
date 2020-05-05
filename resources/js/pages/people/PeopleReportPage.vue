<template>
    <div class="row mb-0 mb-sm-2">
        <div class="col-xl-6">

            <!-- Registrations -->
            <numbers-widget
                :from-date="fromDate"
                :to-date="toDate"
            />

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
import NumbersWidget from '@/components/people/reporting/NumbersWidget'
import GenderDistributionWidget from '@/components/people/reporting/GenderDistributionWidget'
import AgeDistributionWidget from '@/components/people/reporting/AgeDistributionWidget'
import NationalityDistribution from '@/components/people/reporting/NationalityDistribution'
export default {
    components: {
        NumbersWidget,
        GenderDistributionWidget,
        AgeDistributionWidget,
        NationalityDistribution
    },
    props: {
        fromDate: {
            required: true
        },
        toDate: {
            required: true
        }
    }
}
</script>
