<template>
    <span class="d-block d-sm-inline">
        <small class="text-muted">{{ lang['people::people.case_number'] }}:</small>
        <span class="pr-2">{{ lang['app.yes'] }}</span>
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