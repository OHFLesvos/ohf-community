<template>
    <b-form-group :label="!hideLabel ? label : null">
        <b-input-group :append="$t('people.age_n', {age: age != null ? age : '...'})">
            <b-form-input
                v-model.trim="modelValue"
                ref="input"
                placeholder="YYYY-MM-DD"
                autocomplete="off"
                pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                required
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
            label: this.$t('people.date_of_birth')
        }
    },
    computed: {
        age () {
            if (this.modelValue && isDateString(this.modelValue)) {
                return dateOfBirthToAge(this.modelValue)
            }
            return null
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
