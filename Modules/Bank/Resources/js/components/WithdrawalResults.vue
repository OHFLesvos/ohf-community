<template>
    <div>
        <person-filter-input
            @submit="search"
            @reset="reset"
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
                ></bank-person-card>
                <b-pagination
                    v-if="totalRows > 0"
                    size="sm"
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    class="mb-0"
                ></b-pagination>
            </template>
            <template v-else>
                <info-alert :message="lang['app.not_found']"></info-alert>
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
const RELOAD_STATS_INTERVAL = 1
import PersonFilterInput from './PersonFilterInput'
import BankPersonCard from './BankPersonCard'
import { handleAjaxError } from '@app/utils'
import InfoAlert from '@app/components/InfoAlert'
import { BPagination } from 'bootstrap-vue'
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
            total: 0,
            registerQuery: '',
            perPage: 0,
            currentPage: 1,
            filter: '',
            busy: false,
            stats: {},
            statsLoaded: false
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
        search(filter) {
            // TODO reset page if filter changes
            this.busy = true
            axios.get(`${this.apiUrl}?filter=${filter}&page=${this.currentPage}`)
                .then((res) => {
                    this.filter = filter
                    this.persons = res.data.data
                    this.totalRows = res.data.meta.total
                    this.perPage = res.data.meta.per_page
                    this.registerQuery = res.data.meta.register_query
                    this.loaded = true
                })
                .catch(err => handleAjaxError(err))
                .then(() => this.busy = false)
        },
        reset() {
            // TODO handle reset always the same
            this.persons = []
            this.totalRows = 0
            this.loaded = false
            this.loadStats()
        },
        loadStats() {
            axios.get(this.statsApiUrl)
                .then(res => {
                    this.stats = res.data
                    this.statsLoaded = true
                })
                .catch(console.error);
        }
    },
    watch: {
        currentPage(val) {
            this.search(this.filter)
        }
    },
    mounted() {
        this.loadStats()
        setInterval(this.loadStats, RELOAD_STATS_INTERVAL * 60 * 1000)
    }
}
</script>