<template>
    <div>
        <!-- Error message -->
        <error-alert :message="error" v-if="error"></error-alert>

        <div class="row">
            <div class="col-lg">

                <!-- Search button -->
                <p>
                    <button type="button" class="btn btn-lg btn-block btn-primary" @click="requestCode" :disabled="busy">
                        <icon :name="searchButtonIcon" :spin="searching"></icon>
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
                        <div v-else class="alert alert-warning">
                            {{ lang['shop::shop.person_assigned_to_card_has_been_deleted'] }}
                        </div>
                    </template>
                    <div v-else-if="!error" class="alert alert-warning">
                        {{ lang['shop::shop.card_not_registered'] }}
                    </div>
                </template>

            </div>
            <div class="col-lg">

                <!-- List of redeemed cards -->
                <shop-cards-list
                    :lang="lang"
                    :handouts="handouts"
                    :loading="loading"
                ></shop-cards-list>

            </div>
        </div>

    </div>
</template>

<script>
    import scanQR from '../../../../../resources/js/qr'
    import showSnackbar from '../../../../../resources/js/snackbar'
    import Icon from '../../../../../resources/js/components/Icon'
    import ErrorAlert from '../../../../../resources/js/components/ErrorAlert'
    import { getAjaxErrorMessage } from '../../../../../resources/js/utils'
    import ShopCardsList from './ShopCardsList'
    import ShopCardDetails from './ShopCardDetails'
    export default {
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
        components: {
            ShopCardsList,
            ShopCardDetails,
            Icon,
            ErrorAlert
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
        },
        mounted() {
            this.loadHandouts()
        },
        watch: {
            handout(val, oldVal) {
                if (val == null && oldVal != null) {
                    this.loadHandouts()
                }
            }
        }
    }
</script>