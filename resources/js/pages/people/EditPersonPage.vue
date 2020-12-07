<template>
    <div>
        <b-form @submit.stop.prevent="onSubmit">
            <person-form-fields
                v-model="person"
                :countries="countries"
            />
            <p>
                <b-button variant="primary" type="submit" :disabled="busy">
                    <font-awesome-icon icon="check"/>
                    {{ $t('app.update') }}
                </b-button>
            </p>
        </b-form>
    </div>
</template>

<script>

import SessionVariable from '@/sessionVariable'
const rememberedFilter = new SessionVariable('bank.withdrawal.filter')

import { BForm, BButton } from 'bootstrap-vue'
import showSnackbar from '@/snackbar'
import PersonFormFields from '@/components/people/PersonFormFields'

import peopleApi from '@/api/people'
export default {
    components: {
        BForm,
        BButton,
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
        value: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            person: JSON.parse(JSON.stringify(this.value)),
            busy: false,
        }
    },
    methods: {
        async onSubmit () {
            this.busy = true
            try {
                let data = await peopleApi.update(this.person.public_id, this.person)
                showSnackbar(data.message);

                const filter = this.person.police_no ? this.person.police_no : `${this.person.name} ${this.person.family_name}`
                rememberedFilter.set(filter)

                window.location.href = this.redirectUrl
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.busy = false
        }
    }
}
</script>
