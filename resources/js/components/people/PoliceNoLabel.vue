<template>
    <div>
        <font-awesome-icon icon="hashtag"/>
        <small class="text-muted">{{ lang['people.police_number'] }}:</small>
        <span class="pr-2">
            <text-highlight :queries="highlightTerms">{{ value }}</text-highlight>
        </span>
    </div>
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