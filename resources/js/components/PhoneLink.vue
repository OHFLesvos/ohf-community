<template>
    <span v-if="value != ''">
        <i class="fa fa-phone"></i> <a :href="telHref(value)">{{ value }}</a>
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
        telHref(val) {
            return `tel:${val}`;
        }
    }
}
</script>