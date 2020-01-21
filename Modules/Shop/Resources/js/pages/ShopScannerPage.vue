<template>
    <div>
        <!-- Error message -->
        <error-alert
            v-if="error"
            :message="error"
        />

        <div class="row">
            <div class="col-lg">

                <!-- Card scanner -->
                <card-scanner-area
                    :busy="searching"
                    :validator="validateCode"
                    @decode="onDecode"
                />

            </div>
            <div class="col-lg">

                <!-- Shop card details -->
                <info-alert
                    v-if="code != null &&
                    searching" :message="this.lang['app.searching']"
                />
                <template v-if="code != null && !searching">
                    <template v-if="handout != null">
                        <template v-if="handout.person != null">
                            <shop-card-details
                                :handout="handout"
                                :lang="lang"
                                :busy="busy"
                                @redeem="redeemCard"
                                @cancel="cancelCard"
                            ></shop-card-details>
                        </template>
                        <warning-alert
                            v-else
                            :message="lang['shop::shop.person_assigned_to_card_has_been_deleted']"
                        />
                    </template>
                    <warning-alert
                        v-else-if="!error"
                        :message="lang['shop::shop.card_not_registered']"
                    />
                </template>

                <!-- List of redeemed cards -->
                <shop-cards-list
                    :handouts="handouts"
                    :loading="loading"
                    :lang="lang"
                ></shop-cards-list>

            </div>
        </div>

    </div>
</template>

<script>
import showSnackbar from '@app/snackbar'
import FontAwesomeIcon from '@app/components/common/FontAwesomeIcon'
import ErrorAlert from '@app/components/alerts/ErrorAlert'
import WarningAlert from '@app/components/alerts/WarningAlert'
import InfoAlert from '@app/components/alerts/InfoAlert'
import { getAjaxErrorMessage } from '@app/utils'
import ShopCardsList from '../components/ShopCardsList'
import ShopCardDetails from '../components/ShopCardDetails'
import CardScannerArea from '../components/CardScannerArea'
import { isAlphaNumeric } from '@app/utils'
export default {
    components: {
        ShopCardsList,
        ShopCardDetails,
        FontAwesomeIcon,
        ErrorAlert,
        WarningAlert,
        InfoAlert,
        CardScannerArea
    },
    props: {
        listCardsUrl: {
            type: String,
            required: true,
        },
        getCardUrl: {
            type: String,
            required: true,
        },
        lang: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            loading: true,
            code: null,
            error: null,
            handout: null,
            searching: false,
            busy: false,
            handouts: [],
        }
    },
    watch: {
        handout(val, oldVal) {
            if (val == null && oldVal != null) {
                this.loadHandouts()
            }
        },
        code(val, oldVal) {
            if (val != null && oldVal != val) {
                this.searchCode(val)
            }
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
        },
        onDecode(value) {
            const code = value.trim()
            if (code.length > 0) {
                this.code = code
            }
        },
        searchCode(code) {
            this.error = null
            this.handout = null
            this.busy = true
            this.searching = true
            const url = `${this.getCardUrl}?code=${code}`
            return axios.get(url)
                .then(res => this.handout = res.data.data)
                .catch(err => {
                    if (!err.response || err.response.status != 404) {
                        this.error = getAjaxErrorMessage(err)
                        console.error(err)
                    }
                })
                .then(() => {
                    this.busy = false
                    this.searching = false
                });
        },
        redeemCard() {
            this.busy = true
            axios.patch(this.handout.redeem_url)
                .then(res => {
                    this.code = null
                    this.handouts.unshift(this.handout)
                    this.handout = null
                    showSnackbar(res.data.message)
                })
                .catch(err => {
                    this.error = getAjaxErrorMessage(err)
                    console.error(err)
                })
                .then(() => this.busy = false)
        },
        cancelCard() {
            if (window.confirm(this.lang['shop::shop.should_card_be_cancelled'])) {
                this.busy = true
                axios.delete(this.handout.cancel_url)
                    .then(res => {
                        this.code = null
                        this.handout = null
                        showSnackbar(res.data.message)
                    })
                    .catch(err => {
                        this.error = getAjaxErrorMessage(err)
                        console.error(err)
                    })
                    .then(() => this.busy = false)
            }
        },
        validateCode(val) {
            return isAlphaNumeric(val)
        }
    }
}
</script>