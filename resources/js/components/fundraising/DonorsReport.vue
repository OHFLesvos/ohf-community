<template>
    <div>
        <h2>
            {{ $t('fundraising.donors') }}
        </h2>
        <div class="row">

            <!-- General donor numbers -->
            <div class="col-md">
                <simple-two-column-list-card
                    :header="$t('app.numbers')"
                    :items="count ? count : []"
                    :loading="!count"
                    :error="countError"
                />
            </div>

            <!-- Countries -->
            <div class="col-md">
                <advanced-two-column-list-card
                    :header="$t('app.countries')"
                    :items="countries ? countries : []"
                    :limit="5"
                    :loading="!countries"
                    :error="countriesError"
                />
            </div>

            <!-- Languages -->
            <div class="col-md">
                <advanced-two-column-list-card
                    :header="$t('app.languages')"
                    :items="languages ? languages : []"
                    :limit="5"
                    :loading="!languages"
                    :error="languagesError"
                />
            </div>

        </div>

        <hr class="mt-0">

        <!-- Date range selector -->
        <date-range-select
            v-model="dateRange"
        />

        <!-- Registrations over time chart -->
        <time-bar-chart
            :title="$t('fundraising.new_donors_registered')"
            :base-url="route('api.fundraising.donors.registrations')"
            :date-from="this.dateRange.from"
            :date-to="this.dateRange.to"
            :granularity="this.dateRange.granularity"
            class="mb-3"
        />

        <time-bar-chart
            :title="$t('fundraising.donations_made')"
            :base-url="route('api.fundraising.donations.registrations')"
            :date-from="this.dateRange.from"
            :date-to="this.dateRange.to"
            :granularity="this.dateRange.granularity"
            class="mb-3"
        />

        <time-bar-chart
            :title="$t('fundraising.total_donations_made')"
            :base-url="route('api.fundraising.donations.registrations')"
            :date-from="this.dateRange.from"
            :date-to="this.dateRange.to"
            :granularity="this.dateRange.granularity"
            :cumulative="true"
            class="mb-3"
        />

    </div>
</template>

<script>
import axios from '@/plugins/axios'
import SimpleTwoColumnListCard from '@/components/common/SimpleTwoColumnListCard'
import AdvancedTwoColumnListCard from '@/components/common/AdvancedTwoColumnListCard'
import TimeBarChart from '@/components/charts/TimeBarChart'
import DateRangeSelect from '@/components/common/DateRangeSelect'
import { getAjaxErrorMessage } from '@/utils'
import moment from 'moment'
export default {
    components: {
        SimpleTwoColumnListCard,
        AdvancedTwoColumnListCard,
        TimeBarChart,
        DateRangeSelect
    },
    data () {
        return {
            count: null,
            countError: null,
            countries: null,
            countriesError: null,
            languages: null,
            languagesError: null,
            dateRange: {
                from: moment().subtract(3, 'months').format(moment.HTML5_FMT.DATE),
                to: moment().format(moment.HTML5_FMT.DATE),
                granularity: 'days',
            }
        }
    },
    mounted () {
        this.countError = null
        axios.get(this.route('api.fundraising.donors.count'))
            .then(res => {
                const data = res.data
                this.count = [
                    {
                        name: this.$t('app.total'),
                        value: data.total
                    },
                    {
                        name: this.$t('app.individual_persons'),
                        value: data.persons
                    },
                    {
                        name: this.$t('app.companies'),
                        value: data.companies
                    },
                    {
                        name: this.$t('app.with_registered_address'),
                        value: data.with_address
                    },
                    {
                        name: this.$t('app.with_registered_email'),
                        value: data.with_email
                    },
                    {
                        name: this.$t('app.with_registered_phone'),
                        value: data.with_phone
                    },
                ]
            })
            .catch(err => this.countError = getAjaxErrorMessage(err))

        this.countriesError = null
        axios.get(this.route('api.fundraising.donors.countries'))
            .then(res => this.countries = res.data)
            .catch(err => this.countriesError = getAjaxErrorMessage(err))

        this.languagesError = null
        axios.get(this.route('api.fundraising.donors.languages'))
            .then(res => this.languages = res.data)
            .catch(err => this.languagesError = getAjaxErrorMessage(err))
    }
}
</script>
