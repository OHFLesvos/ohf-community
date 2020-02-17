<template>
    <div
        v-if="loaded"
        id="stats"
        class="text-center lead my-5"
    >
        <p
            v-if="stats.number_of_persons_served"
            v-html="$t('people.num_persons_served_handing_out_coupons', {
                persons: stats.number_of_persons_served,
                coupons: stats.number_of_coupons_handed_out,
            })"
        ></p>
        <template v-else>
            {{ $t('people.not_yet_served_any_persons') }}
        </template>
        <template v-if="stats.limited_coupons">
            <p
                v-for="(v, k) in stats.limited_coupons"
                :key="k"
                v-html="$t('coupons.coupons_handed_out_n_t', {
                        coupon: v.coupon,
                        count: v.count,
                        limit: v.limit
                    })"
            ></p>
        </template>
    </div>
</template>

<script>
const DEFAULT_RELOAD_INTERVAL = 60
export default {
    props: {
        apiUrl: {
            type: String,
            required: true
        },
        reloadInterval: {
            type: Number,
            required: false,
            default: DEFAULT_RELOAD_INTERVAL, // in seconds
            validator: val => val > 0
        }
    },
    data() {
        return {
            stats: {},
            loaded: false,
            interval: null
        }
    },
    mounted() {
        this.loadStats()
        this.interval = setInterval(this.loadStats, this.reloadInterval * 1000)
    },
    beforeDestroy() {
        if (this.interval != null) {
            clearInterval(this.interval)
        }
    },
    methods: {
        loadStats() {
            axios.get(this.apiUrl)
                .then(res => {
                    this.stats = res.data
                    this.loaded = true
                })
                .catch(console.error);
        }
    }
}
</script>