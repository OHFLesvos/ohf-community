<template>
    <a
        :href="url"
        title="Edit"
    >
        <font-awesome-icon icon="edit"/>
    </a>
</template>

<script>
export default {
    props: {
        url: {
            type: String,
            required: true
        }
    }
}
</script>