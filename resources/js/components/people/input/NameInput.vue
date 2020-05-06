<template>
    <b-form-group :label="!hideLabel ? label : null">
        <b-form-input
            v-model.trim="modelValue"
            ref="input"
            :placeholder="hideLabel ? label : null"
            autocomplete="off"
            required
        />
    </b-form-group>
</template>

<script>
export default {
    props: {
        value: {
            required: true
        },
        hideLabel: Boolean
    },
    data () {
        return {
            modelValue: this.value,
            label: this.$t('people.name')
        }
    },
    watch: {
        modelValue (val) {
            this.$emit('input', val)
        }
    },
    methods: {
        focus () {
            this.$refs.input.focus()
        }
    }
}
</script>
