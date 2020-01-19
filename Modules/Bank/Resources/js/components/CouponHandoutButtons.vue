<template>
    <div class="form-row">
        <template v-if="couponTypes.length > 0">
            <div
                v-for="coupon in couponTypes"
                :key="coupon.id"
                class="col-sm-auto mb-2"
            >
                <coupon-handout-button
                    :coupon="coupon"
                    :lang="lang"
                    :disabled="disabled"
                />
            </div>
        </template>
        <em
            v-else
            class="pb-2 px-2"
        >
            {{ lang['people::people.no_coupons_defined'] }}
        </em>
    </div>
</template>

<script>
import CouponHandoutButton from './CouponHandoutButton'
export default {
    components: {
        CouponHandoutButton
    },
    props: {
        couponTypes: {
            type: Array,
            required: true
        },
        disabled: Boolean,
        lang: {
            type: Object,
            required: true
        }
    }
}
</script>