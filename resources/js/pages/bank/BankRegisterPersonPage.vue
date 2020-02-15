<template>
    <b-form @submit="onSubmit">
        <div class="form-row">

            <!-- Name -->
            <div class="col-md">
                <b-form-group :label="lang['people.name']">
                    <b-form-input
                        v-model="person.name"
                        required
                        autofocus
                        ref="name"
                    />
                </b-form-group>
            </div>

            <!-- Family Name -->
            <div class="col-md">
                <b-form-group :label="lang['people.family_name']">
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
                    :lang="lang"
                />
            </div>

            <!-- Date of birth -->
            <div class="col-md-auto">
                <date-of-birth-input
                    v-model="person.date_of_birth"
                    :lang="lang"
                />
            </div>

            <!-- Nationality -->
            <div class="col-md">
                <nationality-input
                    v-model="person.nationality"
                    :lang="lang"
                    :countries="countries"
                />
            </div>

            <!-- Police number -->
            <div class="col-lg">
                <police-number-input
                    v-model="person.police_no"
                    :lang="lang"
                />
            </div>

        </div>

        <div class="form-row">

            <!-- Remarks -->
            <div class="col-md">
                <b-form-group :label="lang['people.remarks']">
                    <b-form-input
                        v-model="person.remarks"
                    />
                </b-form-group>
            </div>

        </div>

		<p>
            <b-button variant="primary" type="submit" :disabled="busy">
                <font-awesome-icon icon="check"/>
                {{ lang['app.register'] }}
            </b-button>
        </p>

    </b-form>
</template>

<script>

// TODO: Register card

import SessionVariable from '@/sessionVariable'
const rememberedFilter = new SessionVariable('bank.withdrawal.filter')

import { BForm, BFormInput, BFormGroup, BButton, BModal } from 'bootstrap-vue'
import showSnackbar from '@/snackbar'
import GenderRadioInput from '@/components/people/GenderRadioInput'
import DateOfBirthInput from '@/components/people/DateOfBirthInput'
import NationalityInput from '@/components/people/NationalityInput'
import PoliceNumberInput from '@/components/people/PoliceNumberInput'
import { handleAjaxError } from '@/utils'
export default {
    components: {
        BForm,
        BFormInput,
        BFormGroup,
        BButton,
        BModal,
        GenderRadioInput,
        DateOfBirthInput,
        NationalityInput,
        PoliceNumberInput
    },
    props: {
        apiUrl: {
            required: true,
            type: String
        },
        redirectUrl: {
            required: true,
            type: String
        },
        countries: {
            type: Array,
            required: true,
        },
        lang: {
            type: Object,
            required: true,
        },
        name: {
            type: String,
            required: false,
            default: ''
        },
        familyName: {
            type: String,
            required: false,
            default: ''
        },
        policeNo: {
            type: String,
            required: false,
            default: ''
        }
    },
    data() {
        return {
            person: {
                name: this.name,
                family_name: this.familyName,
                gender: '',
                date_of_birth: '',
                police_no: this.policeNo,
                nationality: '',
                remarks: '',
            },
            busy: false,
        }
    },
    methods: {
        onSubmit(evt) {
            evt.preventDefault()
            this.busy = true
            axios.post(this.apiUrl, this.person)
                .then(response => {
                    var data = response.data
                    showSnackbar(data.message);

                    const filter = this.person.police_no ? this.person.police_no : `${this.person.name} ${this.person.family_name}`
                    rememberedFilter.set(filter)

                    this.$bvModal.msgBoxConfirm('Do you want to add another family nember?', {
                            title: 'Add family member',
                            okTitle: 'Yes',
                            cancelTitle: 'No',
                            centered: true
                        })
                        .then(value => {
                            if (value) {
                                this.person.name = ''
                                this.person.gender = ''
                                this.person.date_of_birth = ''
                                this.person.remarks = ''
                                this.$refs.name.focus()
                            } else {
                                window.location.href = this.redirectUrl
                            }
                        })
                })
                .catch(err => handleAjaxError(err))
                .then(() => this.busy = false)
        }
    }
}
</script>