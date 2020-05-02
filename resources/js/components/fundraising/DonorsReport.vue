<template>
    <div>
        <div class="row">

            <!-- General donor numbers -->
            <div class="col">
                <b-card
                    v-if="count"
                    no-body
                    :header="$t('fundraising.donors')"
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
                            :value1="$t('app.with_address')"
                            :value2="count.with_address"
                        />
                        <two-column-list-group-item
                            :value1="$t('app.with_email')"
                            :value2="count.with_email"
                        />
                        <two-column-list-group-item
                            :value1="$t('app.with_phone')"
                            :value2="count.with_phone"
                        />
                    </b-list-group>
                </b-card>
                <p v-else>
                    <em>{{ $t('app.loading') }}</em>
                </p>
            </div>

            <!-- Countries -->
            <div class="col">
                <b-card
                    v-if="countries"
                    no-body
                    :header="$t('app.countries')"
                    class="mb-4"
                >
                    <b-list-group flush>
                        <b-list-group-item
                            v-for="(amount, country) in selectedCountries"
                            :key="country"
                            class="d-flex justify-content-between align-items-center"
                        >
                            <span>{{ country }}</span>
                            <span>
                                {{ amount }} &nbsp;
                                <small class="text-muted">{{ Math.round(amount / countriesTotal * 100) }}%</small>
                            </span>
                        </b-list-group-item>
                        <b-list-group-item
                            href="javascript:;"
                            @click="topTen = !topTen"
                        >
                            <em>{{ topTen ? $t('app.show_all') : $t('app.shop_top_ten') }}</em>
                        </b-list-group-item>
                    </b-list-group>
                </b-card>
                <p v-else>
                    <em>{{ $t('app.loading') }}</em>
                </p>
            </div>

            <!-- Languages -->
            <div class="col">
                <b-card
                    v-if="languages"
                    no-body
                    :header="$t('app.languages')"
                    class="mb-4"
                >
                    <b-list-group flush>
                        <b-list-group-item
                            v-for="(amount, language) in selectedLanguages"
                            :key="language"
                            class="d-flex justify-content-between align-items-center"
                        >
                            <span>{{ language }}</span>
                            <span>
                                {{ amount }} &nbsp;
                                <small class="text-muted">{{ Math.round(amount / languageTotal * 100) }}%</small>
                            </span>
                        </b-list-group-item>
                        <b-list-group-item
                            href="javascript:;"
                            @click="topTen = !topTen"
                        >
                            <em>{{ topTen ? $t('app.show_all') : $t('app.shop_top_ten') }}</em>
                        </b-list-group-item>
                    </b-list-group>
                </b-card>
                <p v-else>
                    <em>{{ $t('app.loading') }}</em>
                </p>
            </div>

        </div>
    </div>
</template>

<script>
import _ from 'lodash'
import axios from '@/plugins/axios'
import TwoColumnListGroupItem from '@/components/common/TwoColumnListGroupItem'
import { handleAjaxError } from '@/utils'
export default {
    components: {
        TwoColumnListGroupItem
    },
    data () {
        return {
            count: null,
            countries: null,
            countriesTotal: null,
            languages: null,
            languageTotal: null,
            topTen: true
        }
    },
    computed: {
        selectedCountries () {
            if (this.topTen) {
                return _.pick(this.countries, Object.keys(this.countries).slice(0, 10))
            }
            return this.countries
        },
        selectedLanguages () {
            if (this.topTen) {
                return _.pick(this.languages, Object.keys(this.languages).slice(0, 10))
            }
            return this.languages
        }
    },
    mounted () {
        axios.get(this.route('api.fundraising.donors.count'))
            .then(res => this.count = res.data)
            .catch(handleAjaxError)
        axios.get(this.route('api.fundraising.donors.countries'))
            .then(res => {
                this.countries = res.data
                this.countriesTotal = Object.values(this.countries).reduce((a, b) => a + b, 0)
            })
            .catch(handleAjaxError)
        axios.get(this.route('api.fundraising.donors.languages'))
            .then(res => {
                this.languages = res.data
                this.languageTotal = Object.values(this.languages).reduce((a, b) => a + b, 0)
            })
            .catch(handleAjaxError)
    }
}
</script>
