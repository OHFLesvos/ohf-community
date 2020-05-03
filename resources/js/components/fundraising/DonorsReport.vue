<template>
    <div>
        <h2>
            {{ $t('fundraising.donors') }}
        </h2>
        <div class="row">

            <!-- General donor numbers -->
            <div class="col-md">
                <b-card
                    v-if="count"
                    no-body
                    :header="$t('app.numbers')"
                    class="mb-4"
                >
                    <b-list-group flush>
                        <two-column-list-group-item
                            :value1="$t('app.total')"
                            :value2="count.total"
                        />
                        <two-column-list-group-item
                            :value1="$t('app.individual_persons')"
                            :value2="count.persons"
                        />
                        <two-column-list-group-item
                            :value1="$t('app.companies')"
                            :value2="count.companies"
                        />
                        <two-column-list-group-item
                            :value1="$t('app.with_registered_address')"
                            :value2="count.with_address"
                        />
                        <two-column-list-group-item
                            :value1="$t('app.with_registered_email')"
                            :value2="count.with_email"
                        />
                        <two-column-list-group-item
                            :value1="$t('app.with_registered_phone')"
                            :value2="count.with_phone"
                        />
                    </b-list-group>
                </b-card>
                <p v-else>
                    <em>{{ $t('app.loading') }}</em>
                </p>
            </div>

            <!-- Countries -->
            <div class="col-md">
                <two-column-list-card
                    v-if="languages"
                    :header="$t('app.countries')"
                    :items="countries"
                    :limit="5"
                />
                 <p v-else>
                    <em>{{ $t('app.loading') }}</em>
                </p>
            </div>

            <!-- Languages -->
            <div class="col-md">
                <two-column-list-card
                    v-if="languages"
                    :header="$t('app.languages')"
                    :items="languages"
                    :limit="5"
                />
                <p v-else>
                    <em>{{ $t('app.loading') }}</em>
                </p>
            </div>

        </div>

        <hr class="mt-0">

        <!-- Date range selector -->
        <div class="form-row">
            <div class="col-sm">
                <b-form-select
                    v-model="dateGranularity"
                    :options="dateGranularities"
                />
            </div>
            <div class="col-sm">
                <b-form-datepicker
                    v-model="fromDate"
                    :placeholder="$t('app.from')"
                    :min="minDate"
                    :max="toDate"
                    class="mb-2"
                />
            </div>
            <div class="col-sm">
                <b-form-datepicker
                    v-model="toDate"
                    :placeholder="$t('app.to')"
                    :min="fromDate"
                    :max="maxDate"
                    class="mb-2"
                />
            </div>
        </div>

        <!-- Registrations over time chart -->
        <div class="row">
            <div class="col">
                <time-bar-chart
                    :title="$t('app.registrations')"
                    :url="registrationsChartUrl"
                    class="mb-3"
                />
            </div>
        </div>
    </div>
</template>

<script>
import axios from '@/plugins/axios'
import TwoColumnListGroupItem from '@/components/common/TwoColumnListGroupItem'
import TwoColumnListCard from '@/components/common/TwoColumnListCard'
import TimeBarChart from '@/components/charts/TimeBarChart'
import { handleAjaxError, ucFirst } from '@/utils'
import moment from 'moment'
export default {
    components: {
        TwoColumnListGroupItem,
        TwoColumnListCard,
        TimeBarChart
    },
    data () {
        return {
            count: null,
            countries: null,
            languages: null,
            dateGranularity: 'days',
            dateGranularities: [
                {
                    value: 'days',
                    text: ucFirst(this.$t('app.days'))
                },
                {
                    value: 'weeks',
                    text: ucFirst(this.$t('app.weeks'))
                },
                {
                    value: 'months',
                    text: ucFirst(this.$t('app.months'))
                },
                {
                    value: 'years',
                    text: ucFirst(this.$t('app.years'))
                }
            ],
            fromDate: moment().subtract(3, 'months').format(moment.HTML5_FMT.DATE),
            toDate: moment().format(moment.HTML5_FMT.DATE),
            minDate: null,
            maxDate: moment().format(moment.HTML5_FMT.DATE)
        }
    },
    computed: {
        registrationsChartUrl () {
            let baseUrl = `${this.route('api.fundraising.donors.registrations')}?granularity=${this.dateGranularity}`
            if (this.fromDate && this.toDate) {
                return `${baseUrl}&from=${this.fromDate}&to=${this.toDate}`
            }
            return baseUrl
        }
    },
    mounted () {
        axios.get(this.route('api.fundraising.donors.count'))
            .then(res => this.count = res.data)
            .catch(handleAjaxError)
        axios.get(this.route('api.fundraising.donors.countries'))
            .then(res => this.countries = res.data)
            .catch(handleAjaxError)
        axios.get(this.route('api.fundraising.donors.languages'))
            .then(res => this.languages = res.data)
            .catch(handleAjaxError)
    }
}
</script>
