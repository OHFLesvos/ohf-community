<template>
    <a
        :href="url"
        title="Edit"
    >
        <icon name="edit"/>
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