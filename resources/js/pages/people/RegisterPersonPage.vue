<template>
    <b-form @submit="onSubmit">

        <person-form-fields
            v-model="person"
            :countries="countries"
            autofocus
        />

		<p>
            <b-button variant="primary" type="submit" :disabled="busy">
                <font-awesome-icon icon="check"/>
                {{ $t('app.register') }}
            </b-button>
        </p>

    </b-form>
</template>

<script>

import SessionVariable from '@/sessionVariable'
const rememberedFilter = new SessionVariable('bank.withdrawal.filter')

import { BForm, BButton, BModal } from 'bootstrap-vue'
import showSnackbar from '@/snackbar'
import PersonFormFields from '@/components/people/PersonFormFields'
import { handleAjaxError } from '@/utils'
export default {
    components: {
        BForm,
        BButton,
        BModal,
        PersonFormFields
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