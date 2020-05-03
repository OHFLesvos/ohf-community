<template>
    <div>
        <h2>
            {{ $t('fundraising.donors') }}
        </h2>
        <div class="row">

            <!-- General donor numbers -->
            <div class="col-md">
                <b-card
                    :no-body="count"
                    :header="$t('app.numbers')"
                    class="mb-4"
                >
                    <b-card-text v-if="!count">
                        <em>{{ $t('app.loading') }}</em>
                    </b-card-text>
                    <template v-else>
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
                    </template>
                </b-card>
            </div>

            <!-- Countries -->
            <div class="col-md">
                <two-column-list-card
                    :header="$t('app.countries')"
                    :items="countries ? countries : []"
                    :limit="5"
                    :loading="!countries"
                />
            </div>

            <!-- Languages -->
            <div class="col-md">
                <two-column-list-card
                    :header="$t('app.languages')"
                    :items="languages ? languages : []"
                    :limit="5"
                    :loading="!languages"
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
            :title="$t('fundraising.donations_made')"
            :base-url="route('api.fundraising.donations.amounts')"
            :date-from="this.dateRange.from"
            :date-to="this.dateRange.to"
            :granularity="this.dateRange.granularity"
            :y-axes-label="$t('app.amount')"
            class="mb-3"
        />

    </div>
</template>

<script>
import axios from '@/plugins/axios'
import TwoColumnListGroupItem from '@/components/common/TwoColumnListGroupItem'
import TwoColumnListCard from '@/components/common/TwoColumnListCard'
import TimeBarChart from '@/components/charts/TimeBarChart'
import DateRangeSelect from '@/components/common/DateRangeSelect'
import { handleAjaxError } from '@/utils'
import moment from 'moment'
export default {
    components: {
        TwoColumnListGroupItem,
        TwoColumnListCard,
        TimeBarChart,
        DateRangeSelect
    },
    data () {
        return {
            count: null,
            countries: null,
            languages: null,
            dateRange: {
                from: moment().subtract(3, 'months').format(moment.HTML5_FMT.DATE),
                to: moment().format(moment.HTML5_FMT.DATE),
                granularity: 'days',
            }
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
