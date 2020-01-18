<template>
    <div id="stats" class="text-center lead my-5">
        <p v-if="stats.numbers" v-html="stats.numbers"></p>
        <template v-else>{{ lang['people::people.not_yet_served_any_persons'] }}</template>
        <template v-if="stats.limitedCoupons">
            <p v-for="(v, k) in stats.limitedCoupons" v-html="v" :key="k"></p>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        lang: {
            type: Object,
            required: true
        },
        stats: {
            type: Object,
            required: true
        },
    }
}
</script>