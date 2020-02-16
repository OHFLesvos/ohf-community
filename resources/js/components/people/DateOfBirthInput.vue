<template>
    <b-form-group :label="$t('people.date_of_birth')">
        <b-input-group :append="ageString">
            <b-form-input
                v-model="date_of_birth"
                pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                placeholder="YYYY-MM-DD"
            />
        </b-input-group>
    </b-form-group>
</template>

<script>
import { BFormGroup, BInputGroup, BFormInput } from 'bootstrap-vue'
import { isDateString, dateOfBirthToAge } from '@/utils'
export default {
    components: {
        BFormGroup,
        BInputGroup,
        BFormInput
    },
    props: {
        value: {
            type: String,
        }
    },
    computed: {
        date_of_birth: {
            get() {
                return this.value
            },
            set(val) {
                this.$emit('input', val)
            }
        },
        ageString() {
            const dob = this.date_of_birth
            if (dob && isDateString(dob)) {
                const age = dateOfBirthToAge(dob)
                if (!isNaN(age) && age >= 0) {
                    return this.$t('people.age_n', {age: age})
                }
            }
            return null
        }
    }
}
</script>