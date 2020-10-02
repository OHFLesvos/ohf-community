<template>
    <span>
        <a
            v-if="iconOnly"
            :href="telHref"
            class="btn btn-light btn-sm"
            ><font-awesome-icon :icon="icon" /></a>
        <template v-else>
            <font-awesome-icon :icon="icon" />
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
        iconOnly: Boolean,
        mobile: Boolean
    },
    computed: {
        telHref () {
            return `tel:${this.value}`
        },
        icon () {
            if (this.mobile) {
                return 'mobile-alt'
            }
            return 'phone'
        }
    }
}
</script>
