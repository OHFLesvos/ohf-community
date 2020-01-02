<template>
    <a :href="url" alt="View" v-if="canView"><strong class="mark-text">{{ name }}</strong></a>
    <strong class="mark-text" v-else>{{ name }}</strong>
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
            name: {
                type: String,
                required: true,
            }
        },
    }
</script>