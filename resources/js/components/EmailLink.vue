<template>
    <span v-if="value != ''">
        <i class="fa fa-envelope"></i> <a :href="mailtoHref(value)">{{ value }}</a>
    </span>
</template>
<script>
export default {
    props: {
        value: {
            required: true,
            type: String
        }
    },
    methods: {
        mailtoHref(val) {
            return `mailto:${val}`;
        }
    }
}
</script>