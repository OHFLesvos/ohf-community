<template>
    <img
        :src="route('api.users.avatar', { user, size: size })"
        class="avatar"
        :width="size"
        :height="size" />
</template>

<script>
export default {
    props: {
        user: {
            required: true
        },
        size: {
            required: false,
            default: 48,
        }
    }
}
</script>
