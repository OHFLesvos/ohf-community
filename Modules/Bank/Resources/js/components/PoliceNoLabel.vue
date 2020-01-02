<template>
    <span class="d-block d-sm-inline">
        <small class="text-muted">{{ lang['people::people.police_number'] }}:</small>
        <span class="pr-2 mark-text">{{ value }}</span>
    </span>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                required: true
            },
            lang: {
                type: Object,
                required: true
            }
        },
    }
</script>