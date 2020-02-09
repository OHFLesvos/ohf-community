<template>
    <div
        v-if="loaded"
        id="stats"
        class="text-center lead my-5"
    >
        <p
            v-if="stats.numbers"
            v-html="stats.numbers"
        ></p>
        <template v-else>
            {{ lang['people.not_yet_served_any_persons'] }}
        </template>
        <template v-if="stats.limitedCoupons">
            <p
                v-for="(v, k) in stats.limitedCoupons"
                :key="k"
                v-html="v"
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
        },
        lang: {
            type: Object,
            required: true
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