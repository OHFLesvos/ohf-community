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
        label: {
            required: true,
            type: String
        },
        query: {
            required: true,
            type: String
        },
        placeId: {
            required: false,
            type: String
        },
        iconOnly: Boolean,
        labelOnly: Boolean
    },
    computed: {
        mapsHref () {
            let str = `https://www.google.com/maps/search/?api=1&query=${this.query}`
            if (this.placeId) {
                str += `&query_place_id=${this.placeId}`
            }
            return encodeURI(str)
        }
    }
}
</script>
