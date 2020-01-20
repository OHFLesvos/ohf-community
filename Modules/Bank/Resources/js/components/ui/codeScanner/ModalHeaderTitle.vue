<template>
    <h5 class="modal-title">
        {{ title }}
    </h5>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        }
    }
}
</script>