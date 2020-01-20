<template>
    <div>
        <!-- Error message -->
        <error-alert
            v-if="error"
            :message="error"
        />

        <div class="row">
            <div class="col-lg">

                <!-- Scanner toggle button -->
                <p>
                    <button
                        v-if="!isScannerEnabled"
                        type="button"
                        class="btn btn-lg btn-block btn-primary"
                        @click="isScannerEnabled = true"
                    >
                        <font-awesome-icon icon="qrcode"/>
                        Enable scanner
                    </button>
                    <button
                        v-if="isScannerEnabled"
                        type="button"
                        class="btn btn-lg btn-block btn-warning"
                        @click="isScannerEnabled = false"
                    >
                        <font-awesome-icon icon="times"/>
                        Stop scanner
                    </button>
                </p>

                <!-- QR Code Scanner -->
                <div
                    v-if="isScannerEnabled"
                    class="mb-2"
                >
                    <qrcode-stream
                        style="width: 100%; height: 100%"
                        :camera="camera"
                        @decode="onDecode"
                    ></qrcode-stream>
                </div>

            </div>
            <div class="col-lg">

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
import { getAjaxErrorMessage } from '@app/utils'
import ShopCardsList from './ShopCardsList'
import ShopCardDetails from './ShopCardDetails'
import { isAlphaNumeric } from '@app/utils'
import { QrcodeStream } from 'vue-qrcode-reader'
export default {
    components: {
        ShopCardsList,
        ShopCardDetails,
        FontAwesomeIcon,
        ErrorAlert,
        WarningAlert,
        QrcodeStream
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
            isScannerEnabled: false,
            camera: 'auto',
        }
    },
    computed: {
        // searchButtonIcon() {
        //     return this.searching ? 'spinner' : 'qrcode'
        // },
        // searchButtonLabel() {
        //     if (this.searching) {
        //         return this.lang['app.searching']
        //     }
        //     if (this.code != null) {
        //         return this.lang['shop::shop.scan_another_card']
        //     }
        //     return this.lang['shop::shop.scan_card']
        // }
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
        async onDecode(value) {
            this.error = null
            this.handout = null
            let code = value.trim()
            if (code.length > 0) {
                this.code = code
                this.turnCameraOff()
                this.searchCode(code)
                    .then(() => this.turnCameraOn())
            } else {
                this.code = null
            }
        },
        turnCameraOn() {
            this.camera = 'auto'
        },
        turnCameraOff() {
            this.camera = 'off'
        },
        timeout(ms) {
            return new Promise(resolve => {
                window.setTimeout(resolve, ms)
            })
        },
        searchCode(code) {
            this.busy = true
            this.searching = true
            return axios.get(`${this.getCardUrl}?code=${code}`)
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