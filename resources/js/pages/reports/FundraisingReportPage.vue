<template>
    <div>
        <!-- Date range selector -->
        <date-range-select
            v-model="dateRange"
        />

        <h2>
            {{ $t('Donors') }}
        </h2>

        <div class="row">

            <!-- General donor numbers -->
            <div class="col-md">
                <simple-two-column-list-card
                    :header="$t('Registered donors')"
                    :headerAddon="$t('since :date', { date: firstDonorRegistration })"
                    :items="count ? count : []"
                    :loading="!count"
                    :error="countError"
                />
            </div>

            <!-- Countries -->
            <div class="col-md">
                <advanced-two-column-list-card
                    :header="$t('Countries')"
                    :items="countries ? countries : []"
                    :limit="5"
                    :loading="!countries"
                    :error="countriesError"
                />
            </div>

            <!-- Languages -->
            <div class="col-md">
                <advanced-two-column-list-card
                    :header="$t('Languages')"
                    :items="languages ? languages : []"
                    :limit="5"
                    :loading="!languages"
                    :error="languagesError"
                />
            </div>

        </div>

        <!-- Registrations over time chart -->
        <time-bar-chart
            :title="$t('New Donors registered')"
            :data="reportApi.fetchDonorRegistrations"
            :date-from="dateRange.from"
            :date-to="dateRange.to"
            :granularity="dateRange.granularity"
            class="mb-3"
        />

        <h2>
            {{ $t('Donations') }}
        </h2>

        <time-bar-chart
            :title="$t('Donations made')"
            :data="reportApi.fetchDonationRegistrations"
            :date-from="dateRange.from"
            :date-to="dateRange.to"
            :granularity="dateRange.granularity"
            class="mb-3"
        />

        <time-bar-chart
            :title="$t('Total donations made')"
            :data="reportApi.fetchDonationRegistrations"
            :date-from="dateRange.from"
            :date-to="dateRange.to"
            :granularity="dateRange.granularity"
            :cumulative="true"
            class="mb-3"
        />

        <b-row>
            <b-col md>
                <doughnut-chart-table-distribution-widget
                    :title="$t('Currencies')"
                    :data="reportApi.fechCurrencyDistribution"
                />
            </b-col>
            <b-col md>
                <doughnut-chart-table-distribution-widget
                    :title="$t('Channels')"
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
            this.firstDonorRegistration = this.dateFormat(data.first)
            return [
                {
                    name: this.$t('Total'),
                    value: data.total
                },
                {
                    name: this.$t('Individual persons'),
                    value: data.persons
                },
                {
                    name: this.$t('Companies'),
                    value: data.companies
                },
                {
                    name: this.$t('with registered address'),
                    value: data.with_address
                },
                {
                    name: this.$t('with registered email address'),
                    value: data.with_email
                },
                {
                    name: this.$t('with registered phone number'),
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
    }
}
</script>
