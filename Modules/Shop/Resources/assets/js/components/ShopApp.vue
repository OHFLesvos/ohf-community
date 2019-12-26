<template>
    <div>
        <!-- Error message -->
        <div class="alert alert-danger" v-if="error">
            {{ error }}
        </div>

        <!-- Search button -->
        <p>
            <button type="button" class="btn btn-lg btn-block btn-primary" @click="requestCode" :disabled="busy">
                <icon :name="code && busy ? 'spinner' : 'qrcode'" :spin="code && busy"></icon>
                {{ lang['shop::shop.scan_card'] }}
            </button>
        </p>

        <template v-if="code != null && !busy">
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
                busy: false
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
                            this.busy = true;
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