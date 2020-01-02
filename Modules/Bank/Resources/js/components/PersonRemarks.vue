<template>
    <div>
        <em class="text-info">{{ value }}</em>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                required: true
            }
        }
    }
</script>