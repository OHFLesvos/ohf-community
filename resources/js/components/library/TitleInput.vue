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
            type: String,
        },
        hideLabel: Boolean
    },
    data () {
        return {
            label: this.$t('app.title'),
            state: null
        }
    },
    computed: {
        modelValue: {
            get() {
                return this.value
            },
            set(val) {
                this.$emit('input', val)
                this.state = this.$refs.input.checkValidity()
            }
        },
        invalidFeedback () {
            return this.$t('validation.required', {attribute: this.label})
        }
    },
    methods: {
        focus () {
            this.$refs.input.focus()
        }
    }
}
</script>
