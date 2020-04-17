<template>
    <button
        v-if="last_handout"
        type="button"
        class="btn btn-secondary btn-sm btn-block"
        :disabled="busy || !returning_possible || disabled"
        @click="undoHandoutCoupon"
    >
        {{ daily_amount }}
        <font-awesome-icon :icon="icon"/>
        {{ name }}
        ({{ last_handout }})
    </button>
    <button
        v-else
        type="button"
        class="btn btn-primary btn-sm btn-block"
        :disabled="busy || disabled"
        @click="handoutCoupon"
    >
        {{ daily_amount }}
        <font-awesome-icon :icon="icon"/>
        {{ name }}
        <font-awesome-icon
            v-if="qr_code_enabled"
            icon="qrcode"
        />

        <code-scanner-modal
            v-if="qr_code_enabled"
            ref="codeScanner"
            :title="$t('people.qr_code_scanner')"
            :validator="validateCode"
            :validator-message="$t('app.only_letters_and_numbers_allowed')"
            @decode="submitScannedCode"
        />

    </button>
</template>

<script>
import axios from '@/plugins/axios'
import { showSnackbar, handleAjaxError } from '@/utils'
import { isAlphaNumeric } from '@/utils'
import CodeScannerModal from '@/components/ui/CodeScannerModal'
export default {
    components: {
        CodeScannerModal
    },
    props: {
        coupon: {
            type: Object,
            required: true,
            validator: function (obj) {
                return 'qr_code_enabled' in obj &&
                    'daily_amount' in obj &&
                    'handout_url' in obj &&
                    'last_handout' in obj &&
                    'returning_possible' in obj &&
                    'icon' in obj &&
                    'name' in obj
            }
        },
        disabled: Boolean
    },
    data() {
        return {
            busy: false,
            scannedCode: null,
            ...this.coupon
        }
    },
    methods: {
        validateCode(val) {
            return isAlphaNumeric(val)
        },
        submitScannedCode(value) {
            this.sendHandoutRequest({
                "amount": this.daily_amount,
                'code': value,
            });
        },
        handoutCoupon(){
            if (this.qr_code_enabled) {
                this.$refs.codeScanner.open()
            } else {
                this.sendHandoutRequest({
                    "amount": this.daily_amount
                });
            }
        },
        sendHandoutRequest(postData) {
            this.busy = true
            axios.post(this.handout_url, postData)
                .then(response => {
                    const data = response.data
                    this.last_handout = data.countdown
                    this.returning_possible = true
                    setTimeout(this.disableCouponReturn, data.return_grace_period * 1000)
                    showSnackbar(data.message, this.$t('app.undo'), 'warning', (element) => {
                        element.style.opacity = 0
                        this.undoHandoutCoupon()
                    });
                })
                .catch(handleAjaxError)
                .then(() => this.busy = false);
        },
        disableCouponReturn() {
            if (this.last_handout) {
                this.returning_possible = false
            }
        },
        undoHandoutCoupon(){
            this.busy = true
            axios.delete(this.handout_url)
                .then(resonse => {
                    const data = resonse.data
                    this.last_handout = null
                    showSnackbar(data.message);
                })
                .catch(handleAjaxError)
                .then(() => this.busy = false);
        }
    }
}
</script>