<template>
    <b-form @submit.stop.prevent="onSubmit">
        <b-card
            :header="$t('app.data')"
            class="shadow-sm mb-4"
            footer-class="text-right">
            <person-form-fields
                ref="formfields"
                v-model="person"
                :countries="countries"
                autofocus
            />
            <template #footer>
                <b-button variant="primary" type="submit" :disabled="busy">
                    <font-awesome-icon icon="check"/>
                    {{ $t('app.register') }}
                </b-button>
            </template>
        </b-card>
    </b-form>
</template>

<script>

import SessionVariable from '@/sessionVariable'
const rememberedFilter = new SessionVariable('bank.withdrawal.filter')

import { BForm, BButton, BCard } from 'bootstrap-vue'
import showSnackbar from '@/snackbar'
import PersonFormFields from '@/components/people/PersonFormFields'

import peopleApi from '@/api/people'
export default {
    components: {
        BForm,
        BButton,
        BCard,
        PersonFormFields
    },
    props: {
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
        async onSubmit () {
            this.busy = true
            try {
                let data = await peopleApi.store(this.person)
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
                            this.$refs.formfields.focus()
                        } else {
                            window.location.href = this.redirectUrl
                        }
                    })
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.busy = false
        }
    }
}
</script>
