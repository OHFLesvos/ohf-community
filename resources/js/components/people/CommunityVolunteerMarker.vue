<template>
    <strong v-if="url != null">
        <a
            :href="url"
            class="text-warning"
        >{{ label }}</a>
    </strong>
    <strong
        v-else
        class="text-warning"
    >
        {{ label }}
    </strong>
</template>

<script>
export default {
    props: {
        url: {
            type: String,
            required: false,
            default: null
        }
    },
    computed: {
        label() {
            return this.$t('cmtyvol.community_volunteer').toUpperCase()
        }
    }
}
</script>
