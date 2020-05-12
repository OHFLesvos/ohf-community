<template>
    <span>
        <a
            v-if="iconOnly"
            :href="mailToHref"
            class="btn btn-light btn-sm"
        ><font-awesome-icon icon="envelope" /></a>
        <template v-else>
            <font-awesome-icon v-if="!labelOnly" icon="envelope" />
            <a :href="mailToHref">{{ value }}</a>
        </template>
    </span>
</template>
<script>
export default {
    props: {
        value: {
            required: true,
            type: String
        },
        iconOnly: Boolean,
        labelOnly: Boolean
    },
    computed: {
        mailToHref () {
            return `mailto:${this.value}`
        }
    }
}
</script>
