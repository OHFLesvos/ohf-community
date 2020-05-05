<template>
    <div>
        <!-- Date range selector -->
        <date-range-select
            v-model="dateRange"
            no-granularity
            class="mb-2"
        />

        <div class="row mb-0 mb-sm-2">
            <div class="col-xl-6">

                <!-- Registrations -->
                <numbers-widget
                    :date-range="dateRange"
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
    </div>
</template>

<script>
import moment from 'moment'
import DateRangeSelect from '@/components/common/DateRangeSelect'
import NumbersWidget from '@/components/people/reporting/NumbersWidget'
import GenderDistributionWidget from '@/components/people/reporting/GenderDistributionWidget'
import AgeDistributionWidget from '@/components/people/reporting/AgeDistributionWidget'
import NationalityDistribution from '@/components/people/reporting/NationalityDistribution'
export default {
    components: {
        DateRangeSelect,
        NumbersWidget,
        GenderDistributionWidget,
        AgeDistributionWidget,
        NationalityDistribution
    },
    data () {
        return {
            dateRange: {
                from: moment().subtract(30, 'days').format(moment.HTML5_FMT.DATE),
                to: moment().format(moment.HTML5_FMT.DATE),
                granularity: 'days',
            }
        }
    }
}
</script>
