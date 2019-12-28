<template>
    <div>
        <!-- Error message -->
        <div class="alert alert-danger" v-if="error">
            {{ error }}
        </div>

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
            <div v-else class="alert alert-warning">
                {{ lang['shop::shop.card_not_registered'] }}
            </div>
        </template>
    </div>
</template>

<script>
    import scanQR from '../../../../../../resources/js/qr'
    import showSnackbar from '../../../../../../resources/js/snackbar'
    import ShopCardDetails from './ShopCardDetails'
    import Icon from './icon'
    export default {
        props: {
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
            ShopCardDetails,
            Icon
        },
        data() {
            return {
                code: null,
                error: null,
                handout: null,
                searching: false,
                busy: false
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
            requestCode() {
                scanQR(content => {
                    this.error = null
                    this.handout = null
                    let code = content.trim()
                    if (code.length > 0) {
                        if (/^[a-zA-Z0-9]+$/.test(code)) {
                            this.code = code
                            this.busy = true
                            this.searching = true
                            axios.get(`${this.getCardUrl}?code=${code}`)
                                .then(res => {
                                    this.handout = res.data.data
                                })
                                .catch(err => {
                                    if (err.response.status != 404) {
                                        console.log(err.response)
                                    }
                                })
                                .then(() => {
                                    this.busy = false
                                    this.searching = false
                                });
                        } else {
                            this.error = 'Invalid code.'
                        }
                    } else {
                        this.code = null
                    }
                });
            },
            redeemCard() {
                this.busy = true
                axios.patch(this.handout.redeem_url)
                    .then(res => {
                        this.code = null
                        this.handout = null
                        showSnackbar(res.data.message)
                    })
                    .catch(err => {
                        console.log(err.response)
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
                            console.log(err.response)
                        })
                        .then(() => {
                            this.busy = false
                        });
                }
            }
        }
    }
</script>