<template>
    <span>
        <a
            v-if="iconOnly"
            :href="telHref"
            class="btn btn-light btn-sm"
            ><font-awesome-icon icon="phone" /></a>
        <template v-else>
            <font-awesome-icon icon="phone" />
            <a :href="telHref">{{ value }}</a>
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
        iconOnly: Boolean
    },
    computed: {
        telHref () {
            return `tel:${this.value}`
        }
    }
}
</script>
