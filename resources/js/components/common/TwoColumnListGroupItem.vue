<template>
    <b-list-group-item class="d-flex justify-content-between">
        <span>{{ value1 }}</span>
        <span>{{ value2 }}</span>
    </b-list-group-item>
</template>

<script>
export default {
    props: {
        value1: {
            required: true,
        },
        value2: {
            required: true,
        }
    }
}
</script>
