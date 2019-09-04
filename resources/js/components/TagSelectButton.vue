<template>
    <b-button :variant="pressed ? 'primary' : 'light'" size="sm" :pressed.sync="pressed" class="mr-2">{{ label }}</b-button>
</template>
<script>
export default {
    props: {
        value: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            pressed: false
        }
    },
    watch: {
        pressed(val, oldVal) {
            this.$emit('toggled', this.value, val)
        }
    }
}
</script>