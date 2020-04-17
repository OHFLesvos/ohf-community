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
            />

        </div>
        <div class="col-md">

            <!-- List of non redeemed cards by day -->
            <non-expired-cards-table
                :summaryUrl="summaryUrl"
                :deleteUrl="deleteUrl"
            />

        </div>
    </div>
</template>

<script>
import ShopCardsList from '@/components/shop/ShopCardsList'
import NonExpiredCardsTable from '@/components/shop/NonExpiredCardsTable'
import { getAjaxErrorMessage } from '@/utils'
import ErrorAlert from '@/components/alerts/ErrorAlert'
import axios from '@/plugins/axios'
export default {
    components: {
        ShopCardsList,
        NonExpiredCardsTable,
        ErrorAlert
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