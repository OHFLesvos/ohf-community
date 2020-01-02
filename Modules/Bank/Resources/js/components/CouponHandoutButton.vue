<template>
    <button
        type="button"
        class="btn btn-secondary btn-sm btn-block"
        @click="undoHandoutCoupon"
        :disabled="busy"
        v-if="coupon.last_handout"
    >
        <!-- TODO disabled -->
        {{ coupon.daily_amount }}
        <icon :name="coupon.icon"></icon>
        {{ coupon.name }}
        ({{ coupon.last_handout }})
    </button>
    <button
        type="button"
        class="btn btn-primary btn-sm btn-block"
        @click="handoutCoupon"
        :disabled="busy"
        :data-min_age="coupon.min_age"
        :data-max_age="coupon.max_age"
        v-else
    >
        {{ coupon.daily_amount }}
        <icon :name="coupon.icon"></icon>
        {{ coupon.name }}
        <icon name="qrcode" v-if="coupon.qr_code_enabled"></icon>
    </button>
</template>

<script>
import { showSnackbar, handleAjaxError } from '@app/utils'
import scanQR from '@app/qr'
export default {
    props: {
        coupon: {
            type: Object,
            required: true
        },
        lang: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            busy: false,
        }
    },
    methods: {
        handoutCoupon(){
            if (this.coupon.qr_code_enabled) {
                scanQR((content) => {
                    // TODO input validation of code
                    this.sendHandoutRequest({
                        "amount": this.coupon.daily_amount,
                        'code': content,
                    });
                });
            } else {
                this.sendHandoutRequest({
                    "amount": this.coupon.daily_amount
                });
            }
        },
        sendHandoutRequest(postData) {
            this.busy = true
            axios.post(this.coupon.handout_url, postData)
                .then(response => {
                    const data = response.data
                    this.coupon.last_handout = data.countdown
                    showSnackbar(data.message, this.lang['app.undo'], 'warning', (element) => {
                        element.style.opacity = 0
                        this.undoHandoutCoupon()
                        // TODO enableFilterSelect();
                    });
                    // TODO enableFilterSelect();
                })
                .catch(handleAjaxError)
                .then(() => this.busy = false);
        },
        undoHandoutCoupon(){
            this.busy = true
            axios.delete(this.coupon.handout_url)
                .then(resonse => {
                    const data = resonse.data
                    this.coupon.last_handout = null
                    showSnackbar(data.message);
                    // TODO enableFilterSelect();
                })
                .catch(handleAjaxError)
                .then(() => this.busy = false);
        }
    }
}
</script>