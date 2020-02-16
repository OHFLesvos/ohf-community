<template>
    <div>
        <div class="form-row">

            <!-- Name -->
            <div class="col-md">
                <b-form-group :label="$t('people.name')">
                    <b-form-input
                        v-model="person.name"
                        required
                        :autofocus="autofocus"
                        ref="name"
                    />
                </b-form-group>
            </div>

            <!-- Family Name -->
            <div class="col-md">
                <b-form-group :label="$t('people.family_name')">
                    <b-form-input
                        v-model="person.family_name"
                        required
                    />
                </b-form-group>
            </div>
        </div>

        <div class="form-row">

            <!-- Gender -->
            <div class="col-md-auto">
                <gender-radio-input
                    v-model="person.gender"
                />
            </div>

            <!-- Date of birth -->
            <div class="col-md-auto">
                <date-of-birth-input
                    v-model="person.date_of_birth"
                />
            </div>

            <!-- Nationality -->
            <div class="col-md">
                <nationality-input
                    v-model="person.nationality"
                    :countries="countries"
                />
            </div>

        </div>

        <div class="form-row">

            <!-- Police number -->
            <div class="col-md">
                <police-number-input
                    v-model="person.police_no"
                />
            </div>

            <!-- Card registration -->
            <div class="col-md">
                <code-card-input
                    v-model="person.card_no"
                />
            </div>

        </div>

        <div class="form-row">

            <!-- Remarks -->
            <div class="col-md">
                <b-form-group :label="$t('people.remarks')">
                    <b-form-input
                        v-model="person.remarks"
                    />
                </b-form-group>
            </div>

        </div>
    </div>
</template>

<script>
import { BFormInput, BFormGroup } from 'bootstrap-vue'
import GenderRadioInput from '@/components/people/GenderRadioInput'
import DateOfBirthInput from '@/components/people/DateOfBirthInput'
import NationalityInput from '@/components/people/NationalityInput'
import PoliceNumberInput from '@/components/people/PoliceNumberInput'
import CodeCardInput from '@/components/people/CodeCardInput'
export default {
    components: {
        BFormInput,
        BFormGroup,
        GenderRadioInput,
        DateOfBirthInput,
        NationalityInput,
        PoliceNumberInput,
        CodeCardInput,
    },
    props: {
        value: {
            type: Object,
            required: true,
        },
        countries: {
            type: Array,
            required: true,
        },
        autofocus: Boolean,
    },
    data() {
        return {
            person: JSON.parse(JSON.stringify(this.value))
        }
    },
    watch: {
        person: {
            handler(val) {
                this.$emit('input', this.person)
            },
            deep: true
        }
    }
}
</script>