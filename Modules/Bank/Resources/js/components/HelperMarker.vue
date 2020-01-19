<template>
    <strong v-if="canView">
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
        canView: Boolean,
        url: {
            type: String,
            required: false,
            default: null
        },
        lang: {
            type: Object,
            required: true
        }
    },
    computed: {
        label() {
            return this.lang['helpers::helpers.helper'].toUpperCase()
        }
    }
}
</script>