<template>
    <div>
        <person-filter-input
            v-model="filter"
            :lang="lang"
            :busy="busy"
            @scan="searchCode"
        />
        <div
            v-if="loaded"
            class="mt-3"
        >
            <template v-if="totalRows > 0">
                <p>
                    Found {{ totalRows }} results.
                </p>
                <bank-person-card
                    v-for="person in persons"
                    :key="person.id"
                    :person="person"
                    :lang="lang"
                    :highlight-terms="searchTerms"
                />
                <b-pagination
                    v-if="totalRows > 0 && perPage > 0"
                    v-model="currentPage"
                    size="sm"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    class="mb-0"
                />
            </template>
            <template v-else>
                <info-alert
                    v-if="message"
                    :message="message"
                />
                <info-alert
                    v-else
                    :message="lang['app.not_found']"
                />
                <register-person-button
                    v-if="canRegisterPerson != null"
                    :url="registerPersonUrlWithQuery"
                    :lang="lang"
                />
            </template>
        </div>
        <bank-stats
            v-else-if="filter.length == 0"
            :api-url="statsApiUrl"
            :lang="lang"
        />
    </div>
</template>

<script>

// TODO Show Family connections
// TODO reset currentPage if filter changes

const FILTER_SESSION_KEY = 'bank.withdrawal.filter'

import { handleAjaxError } from '@app/utils'

import PersonFilterInput from './PersonFilterInput'
import BankPersonCard from './BankPersonCard'
import RegisterPersonButton from './RegisterPersonButton'
import BankStats from './BankStats'
import InfoAlert from '@app/components/InfoAlert'
import { BPagination } from 'bootstrap-vue'

import { EventBus } from '@app/event-bus.js';

export default {
    components: {
        PersonFilterInput,
        BankPersonCard,
        RegisterPersonButton,
        BankStats,
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
        }
    }
}
</script>