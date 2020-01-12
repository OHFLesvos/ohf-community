<template>
    <button
        type="button"
        class="btn btn-secondary btn-sm btn-block"
        @click="undoHandoutCoupon"
        :disabled="busy || !returning_possible || disabled"
        v-if="last_handout"
    >
        {{ daily_amount }}
        <icon :name="icon"></icon>
        {{ name }}
        ({{ last_handout }})
    </button>
    <button
        type="button"
        class="btn btn-primary btn-sm btn-block"
        @click="handoutCoupon"
        :disabled="busy || disabled"
        v-else
    >
        {{ daily_amount }}
        <icon :name="icon"></icon>
        {{ name }}
        <icon name="qrcode" v-if="qr_code_enabled"></icon>
    </button>
</template>

<script>
import { showSnackbar, handleAjaxError } from '@app/utils'
import scanQR from '@app/qr'
export default {
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
        lang: {
            type: Object,
            required: true
        },
        disabled: Boolean
    },
    data() {
        return {
            busy: false,
            ...this.coupon
        }
    },
    methods: {
        handoutCoupon(){
            if (this.qr_code_enabled) {
                scanQR((content) => {
                    this.sendHandoutRequest({
                        "amount": this.daily_amount,
                        'code': content,
                    });
                });
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
                    showSnackbar(data.message, this.lang['app.undo'], 'warning', (element) => {
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