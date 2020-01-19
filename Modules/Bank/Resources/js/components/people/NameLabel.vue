<template>
    <a
        v-if="canView"
        :href="url"
    >
        <strong>
            <text-highlight :queries="highlightTerms">{{ value }}</text-highlight>
        </strong>
    </a>
    <strong v-else>
        <text-highlight :queries="highlightTerms">{{ value }}</text-highlight>
    </strong>
</template>

<script>
import TextHighlight from 'vue-text-highlight';
export default {
    components: {
        TextHighlight
    },
    props: {
        canView: Boolean,
        url: {
            type: String,
            required: false,
            default: null
        },
        value: {
            type: String,
            required: true,
        },
        highlightTerms: {
            type: Array,
            required: false,
            default: []
        }
    }
}
</script>