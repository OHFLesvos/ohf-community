<template>
    <div>
        <!-- Error message -->
        <error-alert
            v-if="error"
            :message="error"
        />

        <div class="row">
            <div class="col-lg">

                <!-- Search button -->
                <p>
                    <button
                        type="button"
                        class="btn btn-lg btn-block btn-primary"
                        :disabled="busy"
                        @click="requestCode"
                    >
                        <font-awesome-icon
                            :icon="searchButtonIcon"
                            :spin="searching"
                        />
                        {{ searchButtonLabel }}
                    </button>
                </p>

                <!-- Shop card details -->
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

            </div>
            <div class="col-lg">

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
import scanQR from '@app/qr'
import showSnackbar from '@app/snackbar'
import FontAwesomeIcon from '@app/components/common/FontAwesomeIcon'
import ErrorAlert from '@app/components/alerts/ErrorAlert'
import WarningAlert from '@app/components/alerts/WarningAlert'
import { getAjaxErrorMessage } from '@app/utils'
import ShopCardsList from './ShopCardsList'
import ShopCardDetails from './ShopCardDetails'
export default {
    components: {
        ShopCardsList,
        ShopCardDetails,
        FontAwesomeIcon,
        ErrorAlert,
        WarningAlert
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
            handouts: []
        }
    },
    computed: {
        searchButtonIcon() {
            return this.searching ? 'spinner' : 'qrcode'
        },
        searchButtonLabel() {
            if (this.searching) {
                return this.lang['app.searching']
            }
            if (this.code != null) {
                return this.lang['shop::shop.scan_another_card']
            }
            return this.lang['shop::shop.scan_card']
        }
    },
    watch: {
        handout(val, oldVal) {
            if (val == null && oldVal != null) {
                this.loadHandouts()
            }
        }
    },
    mounted() {
        this.loadHandouts()
    },
    methods: {
        loadHandouts() {
            axios.get(this.listCardsUrl)
                .then(res => {
                    this.handouts = res.data.data
                })
                .catch(err => {
                    this.error = getAjaxErrorMessage(err)
                })
                .then(() => {
                    this.loading = false
                })
        },
        requestCode() {
            scanQR(content => {
                this.error = null
                this.handout = null
                let code = content.trim()
                if (code.length > 0) {
                    this.code = code
                    this.busy = true
                    this.searching = true
                    axios.get(`${this.getCardUrl}?code=${code}`)
                        .then(res => {
                            this.handout = res.data.data
                        })
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
                } else {
                    this.code = null
                }
            }, value => {
                if (!/^[a-zA-Z0-9]+$/.test(value)) {
                    throw 'Only letters and numbers are allowed!'
                }
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
                .then(() => {
                    this.busy = false
                });
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
                    .then(() => {
                        this.busy = false
                    });
            }
        }
    }
}
</script>