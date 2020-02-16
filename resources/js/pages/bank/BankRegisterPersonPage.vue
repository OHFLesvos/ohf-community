<template>
    <b-form @submit="onSubmit">

        <div class="form-row">

            <!-- Name -->
            <div class="col-md">
                <b-form-group :label="$t('people.name')">
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
                <p class="mb-2">{{ $t('people.code_card') }}</p>
                <b-button
                    :variant="person.card_no ? 'secondary' : 'primary'"
                    @click="$refs.codeScanner.open()"
                >
                    <font-awesome-icon icon="qrcode"/>
                </b-button>
                <template v-if="person.card_no != ''">
                    {{ person.card_no }}
                    <b-button
                        variant="warning"
                        @click="person.card_no = ''"
                    >
                        <font-awesome-icon icon="trash"/>
                    </b-button>
                </template>
                <span
                    v-else
                    class="text-muted"
                >
                    {{ $t('app.no_cards_registered') }}
                </span>
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

		<p>
            <b-button variant="primary" type="submit" :disabled="busy">
                <font-awesome-icon icon="check"/>
                {{ $t('app.register') }}
            </b-button>
        </p>

        <code-scanner-modal
            ref="codeScanner"
            :title="$t('people.qr_code_scanner')"
            :validator="validateCode"
            :validator-message="$t('app.only_letters_and_numbers_allowed')"
            @decode="assignCard"
        />

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
import { handleAjaxError, isAlphaNumeric } from '@/utils'
import CodeScannerModal from '@/components/ui/CodeScannerModal'

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
        PoliceNumberInput,
        CodeScannerModal
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
                card_no: ''
            },
            busy: false,
        }
    },
    methods: {
        validateCode(val) {
            return isAlphaNumeric(val)
        },
        assignCard(val) {
            this.person.card_no = val
        },
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
                                this.person.card_no = ''
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