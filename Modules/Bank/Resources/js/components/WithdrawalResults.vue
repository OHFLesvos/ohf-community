<template>
    <div>
        <person-filter-input
            @scan="searchCode"
            v-model="filter"
            :lang="lang"
            :busy="busy"
        ></person-filter-input>
        <div v-if="loaded" class="mt-3">
            <template v-if="totalRows > 0">
                <p>Found {{ totalRows }} results.</p>
                <bank-person-card
                    v-for="person in persons"
                    :key="person.id"
                    :person="person"
                    :lang="lang"
                    :highlight-terms="searchTerms"
                ></bank-person-card>
                <b-pagination
                    v-if="totalRows > 0 && perPage > 0"
                    size="sm"
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    class="mb-0"
                ></b-pagination>
            </template>
            <template v-else>
                <info-alert v-if="message" :message="message"></info-alert>
                <info-alert v-else :message="lang['app.not_found']"></info-alert>
                <p v-if="canRegisterPerson != null">
                    <a :href="registerPersonUrlWithQuery" class="btn btn-primary">
                        <icon name="plus-circle"></icon>
                        {{ lang['people::people.register_a_new_person'] }}
                    </a>
                </p>
            </template>
        </div>
        <div id="stats" class="text-center lead my-5" v-else-if="statsLoaded">
            <p v-if="stats.numbers" v-html="stats.numbers"></p>
            <template v-else>{{ lang['people::people.not_yet_served_any_persons'] }}</template>
            <template v-if="stats.limitedCoupons">
                <p v-for="(v, k) in stats.limitedCoupons" v-html="v" :key="k"></p>
            </template>
        </div>
    </div>
</template>

<script>

// TODO Show Family connections
// TODO reset currentPage if filter changes

const RELOAD_STATS_INTERVAL = 1
const FILTER_SESSION_KEY = 'bank.withdrawal.filter'

import PersonFilterInput from './PersonFilterInput'
import BankPersonCard from './BankPersonCard'
import { handleAjaxError } from '@app/utils'
import InfoAlert from '@app/components/InfoAlert'
import { BPagination } from 'bootstrap-vue'
import { EventBus } from '@app/event-bus.js';
export default {
    components: {
        PersonFilterInput,
        BankPersonCard,
        InfoAlert,
        BPagination
    },
    props: {
        apiUrl: {
            required: true,
            type: String
        },
        statsApiUrl: {
            required: true,
            type: String
        },
        lang: {
            type: Object,
            required: true
        },
        canRegisterPerson: Boolean,
        registerPersonUrl: {
            type: String,
            required: false,
            default: null
        }
    },
    data() {
        return {
            persons: [],
            loaded: false,
            totalRows: 0,
            registerQuery: '',
            perPage: 0,
            currentPage: 1,
            filter: this.defaultFilter(),
            busy: false,
            stats: {},
            statsLoaded: false,
            message: null,
            searchTerms: []
        }
    },
    computed: {
        registerPersonUrlWithQuery() {
            let str = this.registerPersonUrl
            if (this.registerQuery.length > 0) {
                str += `?${this.registerQuery}`
            }
            return str
        }
    },
    methods: {
        defaultFilter() {
            let filter = sessionStorage.getItem(FILTER_SESSION_KEY)
            if (filter !== undefined && filter != null && filter.length > 0) {
                return filter
            }
            return ''
        },
        search(filter) {
            this.busy = true
            this.message = null
            this.searchTerms = []
            sessionStorage.removeItem(FILTER_SESSION_KEY)
            axios.get(`${this.apiUrl}?filter=${filter}&page=${this.currentPage}`)
                .then((res) => {
                    this.persons = res.data.data
                    this.totalRows = res.data.meta.total
                    this.perPage = res.data.meta.per_page
                    this.registerQuery = res.data.meta.register_query
                    this.loaded = true
                    if (this.persons.length > 0) {
                        sessionStorage.setItem(FILTER_SESSION_KEY, filter)
                    }
                    if (res.data.meta.terms.length > 0) {
                        this.searchTerms = res.data.meta.terms
                    }
                })
                .catch(err => handleAjaxError(err))
                .then(() => this.busy = false)
                .then(() => {
                    if (this.totalRows == 0) {
                        EventBus.$emit('zero-results');
                    }
                })
        },
        searchCode(code) {
            this.busy = true
            this.message = null
            this.searchTerms = []
            sessionStorage.removeItem(FILTER_SESSION_KEY)
            axios.get(`${this.apiUrl}?card_no=${code}`)
                .then((res) => {
                    this.loaded = true
                    this.perPage = 0
                    this.registerQuery = ''
                    if (res.data.data) {
                        this.persons = [ res.data.data ]
                        this.totalRows = 1
                    } else {
                        this.persons = []
                        this.totalRows = 0
                        if (res.data.message) {
                            this.message = res.data.message
                        }
                    }
                })
                .catch(err => handleAjaxError(err))
                .then(() => this.busy = false)
        },
        reset() {
            this.persons = []
            this.totalRows = 0
            this.loaded = false
            this.message = null
            sessionStorage.removeItem(FILTER_SESSION_KEY)
            this.loadStats()
        },
        loadStats() {
            if (!this.loaded) {
                axios.get(this.statsApiUrl)
                    .then(res => {
                        this.stats = res.data
                        this.statsLoaded = true
                    })
                    .catch(console.error);
            }
        }
    },
    watch: {
        currentPage(val) {
            this.search(this.filter)
        },
        filter(val, oldVal) {
            if (val.length > 0) {
                this.search(val)
            } else if (oldVal.length > 0) {
                this.reset()
            }
        }
    },
    mounted() {
        if (this.filter.length > 0) {
            this.search(this.filter)
        } else {
            this.loadStats()
        }
        setInterval(this.loadStats, RELOAD_STATS_INTERVAL * 60 * 1000)
    }
}
</script>