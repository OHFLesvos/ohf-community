<template>
    <span class="d-block d-sm-inline">
        <small class="text-muted">{{ lang['people::people.police_number'] }}:</small>
        <span class="pr-2">
            <text-highlight :queries="highlightTerms">{{ value }}</text-highlight>
        </span>
    </span>
</template>

<script>
import TextHighlight from 'vue-text-highlight';
export default {
    components: {
        TextHighlight
    },
    props: {
        value: {
            type: String,
            required: true
        },
        highlightTerms: {
            type: Array,
            required: false,
            default: []
        },
        lang: {
            type: Object,
            required: true
        }
    }
}
</script>