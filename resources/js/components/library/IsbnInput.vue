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
            pattern="^(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$"
            :state="state"
        />
    </b-form-group>
</template>

<script>
import { isValidIsbn } from '@/utils'
export default {
    props: {
        value: {
            type: String,
        },
        hideLabel: Boolean
    },
    data () {
        return {
            label: this.$t('library.isbn'),
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
                this.state = this.validationState(val)
            }
        },
        invalidFeedback () {
            if (this.modelValue) { /* NoOp */ }
            if (this.$refs.input && this.$refs.input.validity.patternMismatch) {
                return this.$t('validation.regex', {attribute: this.label})
            }
            return this.$t('validation.isbn', {attribute: this.label})
        }
    },
    methods: {
        validationState (val) {
            if (this.$refs.input.checkValidity()) {
                if (val.length > 0) {
                    return isValidIsbn(val)
                }
                return null
            }
            return false
        },
        focus () {
            this.$refs.input.focus()
        }
    }
}
</script>
