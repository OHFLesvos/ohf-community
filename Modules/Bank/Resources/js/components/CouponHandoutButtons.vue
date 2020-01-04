<template>
    <div class="form-row">
        <template v-if="couponTypes.length > 0">
            <div class="col-sm-auto mb-2" v-for="coupon in couponTypes" :key="coupon.id">
                <coupon-handout-button
                    :coupon="coupon"
                    :lang="lang"
                ></coupon-handout-button>
            </div>
        </template>
        <em class="pb-2 px-2" v-else>
            {{ lang['people::people.no_coupons_defined'] }}
        </em>
    </div>
</template>

<script>
import CouponHandoutButton from './CouponHandoutButton'
export default {
    props: {
        couponTypes: {
            type: Array,
            required: true
        },
        lang: {
            type: Object,
            required: true
        }
    },
    components: {
        CouponHandoutButton
    }
}
</script>