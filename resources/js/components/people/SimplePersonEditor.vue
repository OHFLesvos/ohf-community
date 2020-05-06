<template>
    <form
        ref="form"
        @submit.stop.prevent="submit()"
    >
        <div class="form-row">

            <!-- Name -->
            <div class="col-md">
                <name-input
                    ref="focusThis"
                    v-model="person.name"
                    hide-label
                />
            </div>

            <!-- Family Name -->
            <div class="col-md">
                <family-name-input
                    v-model="person.family_name"
                    hide-label
                />
            </div>

        </div>
        <div class="form-row">

            <!-- Gender -->
            <div class="col-md">
                <gender-radio-input
                    v-model="person.gender"
                    hide-label
                />
            </div>

            <!-- Date of birth -->
            <div class="col-md">
                <date-of-birth-input
                    v-model="person.date_of_birth"
                    hide-label
                />
            </div>

        </div>

        <div class="form-row">

            <!-- Nationality -->
            <div class="col-md">
                <nationality-input
                    v-model="person.nationality"
                    hide-label
                />
            </div>

            <!-- Police number -->
            <div class="col-md">
                <police-number-input
                    v-model="person.police_no"
                    hide-label
                />
            </div>

        </div>
    </form>
</template>

<script>
import NameInput from '@/components/people/input/NameInput'
import FamilyNameInput from '@/components/people/input/FamilyNameInput'
import GenderRadioInput from '@/components/people/input/GenderRadioInput'
import DateOfBirthInput from '@/components/people/input/DateOfBirthInput'
import NationalityInput from '@/components/people/input/NationalityInput'
import PoliceNumberInput from '@/components/people/input/PoliceNumberInput'
export default {
    components: {
        NameInput,
        FamilyNameInput,
        GenderRadioInput,
        DateOfBirthInput,
        NationalityInput,
        PoliceNumberInput
    },
    props: {
        value: {
            type: Object,
            required: false,
            default: function () {
                return {}
            }
        }
    },
    data () {
        return {
            person: {
                name: this.value.name,
                family_name: this.value.family_name,
                gender: this.value.gender,
                date_of_birth: this.value.date_of_birth,
                nationality: this.value.nationality,
                police_no: this.value.police_no
            }
        }
    },
    methods: {
        focus () {
            this.$refs.focusThis.focus()
        },
        submit () {
            if (!this.checkFormValidity()) {
                alert('Form is not valid')
                return
            }
            this.$emit('submit', this.person)
        },
        checkFormValidity () {
            return this.$refs.form.checkValidity()
        },
        reset() {
            this.person = {}
        }
    }
}
</script>
