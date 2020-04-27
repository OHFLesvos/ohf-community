<template>
    <b-button
        :variant="pressed ? 'primary' : 'light'"
        size="sm"
        :pressed.sync="pressed"
        class="mr-2"
    >
        {{ label }}
    </b-button>
</template>
<script>
export default {
    props: {
        value: {
            type: String,
            required: true
        },
        label: {
            type: String,
            required: true
        },
        toggled: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    data () {
        return {
           pressed: this.toggled
        }
    },
    watch: {
        pressed (val) {
            this.$emit('toggled', this.value, val)
        }
    }
}
</script>
