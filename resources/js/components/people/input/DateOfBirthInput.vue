<template>
    <b-form-group
        :label="!hideLabel ? label : null"
        :state="state"
        :invalid-feedback="invalidFeedback"
    >
        <b-input-group :append="$t('people.age_n', {age: age != null ? age : '...'})">
            <b-form-input
                v-model="modelValue"
                trim
                ref="input"
                placeholder="YYYY-MM-DD"
                autocomplete="off"
                pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                required
                :state="state"
            />
        </b-input-group>
    </b-form-group>
</template>

<script>
import { isDateString, dateOfBirthToAge } from '@/utils'
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
            label: this.$t('people.date_of_birth'),
            state: null
        }
    },
    computed: {
        age () {
            if (this.modelValue && isDateString(this.modelValue)) {
                return dateOfBirthToAge(this.modelValue)
            }
            return null
        },
        invalidFeedback () {
            if (this.modelValue) { /* NoOp */ }
            if (this.$refs.input && this.$refs.input.validity.patternMismatch) {
                return this.$t('validation.date', {attribute: this.label})
            }
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
