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
                    :validator="isAlphaNumeric"
                    :validator-message="$t('app.only_letters_and_numbers_allowed')"
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
                    :message="$t('app.searching')"
                />

                <!-- Shop card details -->
                <template v-if="code != null && !searching">
                    <template v-if="handout != null">
                        <template v-if="handout.person != null">
                            <shop-card-details
                                :handout="handout"
                                :busy="busy"
                                @redeem="redeemCard"
                                @cancel="cancelCard"
                            ></shop-card-details>
                        </template>
                        <warning-alert
                            v-else
                            :message="$t('shop.person_assigned_to_card_has_been_deleted')"
                        />
                    </template>
                    <warning-alert
                        v-else-if="!error"
                        :message="$t('shop.card_not_registered')"
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
import ShopCardDetails from '@/components/shop/ShopCardDetails'
import CardScannerArea from '@/components/shop/CardScannerArea'
import { isAlphaNumeric } from '@/utils'
import shopApi from '@/api/shop'
export default {
    components: {
        ShopCardDetails,
        ErrorAlert,
        WarningAlert,
        InfoAlert,
        CardScannerArea
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
                ? this.$t('shop.please_scan_next_card')
                : this.$t('shop.please_enable_scanner_to_scan_cards')
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
        async searchCode(code) {
            this.error = null
            this.handout = null
            this.busy = true
            this.searching = true
            try {
                let data = await shopApi.findCard(code)
                this.handout = data ? data.data : null
            } catch (err) {
                this.error = err
            }
            this.busy = false
            this.searching = false
        },
        async redeemCard() {
            this.busy = true
            try {
                let data = await shopApi.redeemCard(this.handout.id)
                this.code = null
                this.handout = null
                showSnackbar(data.message)
            } catch (err) {
                this.error = err
            }
            this.busy = false
        },
        async cancelCard() {
            if (window.confirm(this.$t('shop.should_card_be_cancelled'))) {
                this.busy = true
                try {
                    let data = await shopApi.cancelCard(this.handout.id)
                    this.code = null
                    this.handout = null
                    showSnackbar(data.message)
                } catch (err) {
                    this.error = err
                }
                this.busy = false
            }
        },
        isAlphaNumeric
    }
}
</script>
