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
            <non-expired-cards-table />

        </div>
    </div>
</template>

<script>
import ShopCardsList from '@/components/shop/ShopCardsList'
import NonExpiredCardsTable from '@/components/shop/NonExpiredCardsTable'
import ErrorAlert from '@/components/alerts/ErrorAlert'
import shopApi from '@/api/shop'
export default {
    components: {
        ShopCardsList,
        NonExpiredCardsTable,
        ErrorAlert
    },
    data () {
        return {
            loading: true,
            error: null,
            handouts: [],
        }
    },
    created () {
        this.loadHandouts()
    },
    methods: {
        async loadHandouts() {
            try {
                let data = await shopApi.listRedeemedToday()
                this.handouts = data.data
            } catch (err) {
                this.error = err
            }
            this.loading = false
        }
    }
}
</script>
