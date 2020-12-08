<template>
    <div>
        <!-- Date range selector -->
        <date-range-select
            v-model="dateRange"
        />

        <h2>
            {{ $t('fundraising.donors') }}
        </h2>

        <div class="row">

            <!-- General donor numbers -->
            <div class="col-md">
                <simple-two-column-list-card
                    :header="$t('fundraising.registered_donors')"
                    :headerAddon="$t('app.since_date', { date: firstDonorRegistration })"
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

        <!-- Registrations over time chart -->
        <time-bar-chart
            :title="$t('fundraising.new_donors_registered')"
            :data="reportApi.fetchDonorRegistrations"
            :date-from="this.dateRange.from"
            :date-to="this.dateRange.to"
            :granularity="this.dateRange.granularity"
            class="mb-3"
        />

        <h2>
            {{ $t('fundraising.donations') }}
        </h2>

        <time-bar-chart
            :title="$t('fundraising.donations_made')"
            :data="donationRegistrations"
            :error="donationRegistrationsError"
            :date-from="this.dateRange.from"
            :date-to="this.dateRange.to"
            :granularity="this.dateRange.granularity"
            class="mb-3"
        />

        <time-bar-chart
            :title="$t('fundraising.total_donations_made')"
            :data="donationRegistrations"
            :error="donationRegistrationsError"
            :date-from="this.dateRange.from"
            :date-to="this.dateRange.to"
            :granularity="this.dateRange.granularity"
            :cumulative="true"
            class="mb-3"
        />

        <b-row>
            <b-col md>
                <doughnut-chart-table-distribution-widget
                    :title="$t('fundraising.currencies')"
                    :data="reportApi.fechCurrencyDistribution"
                />
            </b-col>
            <b-col md>
                <doughnut-chart-table-distribution-widget
                    :title="$t('fundraising.channels')"
                    :data="reportApi.fetchChannelDistribution"
                />
            </b-col>
        </b-row>
    </div>
</template>

<script>
import SimpleTwoColumnListCard from '@/components/ui/SimpleTwoColumnListCard'
import AdvancedTwoColumnListCard from '@/components/ui/AdvancedTwoColumnListCard'
import TimeBarChart from '@/components/charts/TimeBarChart'
import DoughnutChartTableDistributionWidget from '@/components/reporting/DoughnutChartTableDistributionWidget'
import DateRangeSelect from '@/components/common/DateRangeSelect'
import moment from 'moment'
import reportApi from '@/api/fundraising/report'
export default {
    components: {
        SimpleTwoColumnListCard,
        AdvancedTwoColumnListCard,
        TimeBarChart,
        DoughnutChartTableDistributionWidget,
        DateRangeSelect
    },
    data () {
        return {
            firstDonorRegistration: null,
            count: null,
            countError: null,
            countries: null,
            countriesError: null,
            languages: null,
            languagesError: null,
            donationRegistrations: null,
            donationRegistrationsError: null,
            dateRange: {
                from: moment().subtract(3, 'months').format(moment.HTML5_FMT.DATE),
                to: moment().format(moment.HTML5_FMT.DATE),
                granularity: 'days',
            },
            reportApi
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
        loadData () {
            this.loadCount()
            this.loadCountries()
            this.loadLanguages()
            this.fetchDonationRegistrations()
        },
        async loadCount () {
            this.countError = null
            try {
                let data = await reportApi.getCount(this.dateRange.to)
                this.count = this.mapCountData(data)
            } catch (err) {
                this.countError = err
            }
        },
        mapCountData (data) {
            this.firstDonorRegistration = moment(data.first).format('LL')
            return [
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
                }
            ]
        },
        async loadCountries () {
            this.countriesError = null
             try {
                let data = await reportApi.getCountries(this.dateRange.to)
                this.countries = data
            } catch (err) {
                this.countriesError = err
            }
        },
        async loadLanguages () {
            this.languagesError = null
            try {
                let data = await reportApi.getLanguages(this.dateRange.to)
                this.languages = data
            } catch (err) {
                this.languagesError = err
            }
        },
        async fetchDonationRegistrations () {
            this.donationRegistrationsError = null
            try {
                let data = await reportApi.fetchDonationRegistrations(this.granularity, this.dateRange.from, this.dateRange.to)
                this.donationRegistrations = data
            } catch (err) {
                this.donationRegistrationsError = err
            }
        },
    }
}
</script>
