<template>
    <img
        :src="url"
        class="avatar"
        :width="size"
        :height="size"
        alt="Avatar"
    />
</template>

<script>
export default {
    props: {
        url: {
            required: true
        },
        size: {
            required: false,
            default: 48,
        }
    }
}
</script>
