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
                    :validator-message="lang['app.only_letters_and_numbers_allowed']"
                    :enabled="scannerEnabled"
                    @decode="onDecode"
                    @enable="scannerEnabled = true"
                    @disable="scannerEnabled = false"
                />

            </div>
            <div class="col-lg">

                <!-- Searching card message -->
                <info-alert
                    v-if="code != null && searching"
                    :message="this.lang['app.searching']"
                />

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
                            :message="lang['shop.person_assigned_to_card_has_been_deleted']"
                        />
                    </template>
                    <warning-alert
                        v-else-if="!error"
                        :message="lang['shop.card_not_registered']"
                    />
                </template>

                <!-- Idle message -->
                <info-alert
                    v-if="code == null || (handout && handout.code_redeemed != null)"
                    :message="idleMessage"
                />

            </div>
        </div>

    </div>
</template>

<script>
import showSnackbar from '@/snackbar'
import ErrorAlert from '@/components/alerts/ErrorAlert'
import WarningAlert from '@/components/alerts/WarningAlert'
import InfoAlert from '@/components/alerts/InfoAlert'
import { getAjaxErrorMessage } from '@/utils'
import ShopCardDetails from '@/components/shop/ShopCardDetails'
import CardScannerArea from '@/components/shop/CardScannerArea'
import { isAlphaNumeric } from '@/utils'
export default {
    components: {
        ShopCardDetails,
        ErrorAlert,
        WarningAlert,
        InfoAlert,
        CardScannerArea
    },
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
    data() {
        return {
            loading: true,
            code: null,
            error: null,
            handout: null,
            searching: false,
            busy: false,
            scannerEnabled: false
        }
    },
    computed: {
        idleMessage() {
            return this.scannerEnabled
                ? this.lang['shop.please_scan_next_card']
                : this.lang['shop.please_enable_scanner_to_scan_cards']
        }
    },
    watch: {
        code(val, oldVal) {
            if (val != null && oldVal != val) {
                this.searchCode(val)
            }
        }
    },
    methods: {
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
            if (window.confirm(this.lang['shop.should_card_be_cancelled'])) {
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