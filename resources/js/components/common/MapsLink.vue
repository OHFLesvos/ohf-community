<template>
    <span>
        <a
            v-if="iconOnly"
            :href="mapsHref"
            class="btn btn-light btn-sm"
            target="_blank"
        ><font-awesome-icon icon="map-marked-alt" /></a>
        <template v-else>
            <font-awesome-icon v-if="!labelOnly" icon="map-marked-alt" />
            <a :href="mapsHref" target="_blank">{{ label }}</a>
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
        label: {
            required: true,
            type: String,
        },
        iconOnly: Boolean,
        labelOnly: Boolean
    },
    computed: {
        mapsHref () {
            return `https://www.google.com/maps/search/?api=1&query=${this.value}`
        }
    }
}
</script>
