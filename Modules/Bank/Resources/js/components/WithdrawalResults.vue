<template>
    <div>
        <person-filter-input
            @submit="search"
            @scan="searchCode"
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

// TODO code cards search / view
// TODO family connections

function highlightText(text) {
    // TODO fix highlighting
    console.log(`Highlight ${text}`)
	$(".mark-text").each(function(idx) {
		var innerHTML = $( this ).html();
		var index = innerHTML.toLowerCase().indexOf(text.toLowerCase());
		if (index >= 0) {
			innerHTML = innerHTML.substring(0,index) + "<mark>" + innerHTML.substring(index,index+text.length) + "</mark>" + innerHTML.substring(index + text.length);
			$( this ).html(innerHTML);
		}
	});
}

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
            totalRows: 0,
            registerQuery: '',
            perPage: 0,
            currentPage: 1,
            filter: '',
            busy: false,
            stats: {},
            statsLoaded: false,
            message: null
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
            this.message = null
            this.loaded = false
            axios.get(`${this.apiUrl}?filter=${filter}&page=${this.currentPage}`)
                .then((res) => {
                    this.filter = filter
                    this.persons = res.data.data
                    this.totalRows = res.data.meta.total
                    this.perPage = res.data.meta.per_page
                    this.registerQuery = res.data.meta.register_query
                    this.loaded = true
                    if (res.data.meta.terms.length > 0) {
                        res.data.meta.terms.forEach(t => highlightText(t))
                    }
                })
                .catch(err => handleAjaxError(err))
                .then(() => this.busy = false)
        },
        searchCode(code) {
            this.busy = true
            this.message = null
            this.loaded = false
            axios.get(`${this.apiUrl}?card_no=${code}`)
                .then((res) => {
                    this.loaded = true
                    this.filter = ''
                    this.perPage = 0
                    this.registerQuery = ''
                    if (res.data.data) {
                        this.persons = [ res.data.data ]
                        this.totalRows = 1
                        // TODO highlight term -> code
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
            // TODO handle reset always the same
            this.persons = []
            this.totalRows = 0
            this.loaded = false
            this.message = null
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