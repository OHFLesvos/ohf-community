<template>
    <b-form-group
        :label="!hideLabel ? label : null"
        :state="state"
        :invalid-feedback="invalidFeedback"
    >
        <b-form-input
            v-model="modelValue"
            trim
            ref="input"
            :placeholder="hideLabel ? label : null"
            autocomplete="off"
            required
            :state="state"
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
            label: this.$t('people.family_name'),
            state: null
        }
    },
    computed: {
        invalidFeedback () {
            return this.$t('validation.required', {attribute: this.label})
        }
    },
    watch: {
        modelValue (val) {
            this.$emit('input', val)
            this.state = this.$refs.input.checkValidity()
        }
    },
    methods: {
        focus () {
            this.$refs.input.focus()
        }
    }
}
</script>
