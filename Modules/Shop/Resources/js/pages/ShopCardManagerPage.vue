<template>
    <div class="row">
        <div class="col-md">

            <!-- Error message -->
            <error-alert
                v-if="error"
                :message="error"
            />

            <!-- List of redeemed cards -->
            <shop-cards-list
                :handouts="handouts"
                :loading="loading"
                :lang="lang"
            />

        </div>
        <div class="col-md">

            <!-- List of non redeemed cards by day -->
            <non-expired-cards-table
                :summaryUrl="summaryUrl"
                :deleteUrl="deleteUrl"
                :lang="lang"
            />

        </div>
    </div>
</template>

<script>
import ShopCardsList from '../components/ShopCardsList'
import NonExpiredCardsTable from '../components/NonExpiredCardsTable'
export default {
    components: {
        ShopCardsList,
        NonExpiredCardsTable
    },
    props: {
        listCardsUrl: {
            type: String,
            required: true,
        },
        summaryUrl: {
            type: String,
            required: true
        },
        deleteUrl: {
            type: String,
            required: true
        },
        lang: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            loading: true,
            error: null,
            handouts: [],
        }
    },
    mounted() {
        this.loadHandouts()
    },
    methods: {
        loadHandouts() {
            axios.get(this.listCardsUrl)
                .then(res => this.handouts = res.data.data)
                .catch(err => this.error = getAjaxErrorMessage(err))
                .then(() => this.loading = false)
        }
    }
}
</script>