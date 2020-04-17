<template>
    <b-form @submit="onSubmit">

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
</template>

<script>

import SessionVariable from '@/sessionVariable'
const rememberedFilter = new SessionVariable('bank.withdrawal.filter')

import { BForm, BButton } from 'bootstrap-vue'
import showSnackbar from '@/snackbar'
import { handleAjaxError } from '@/utils'
import PersonFormFields from '@/components/people/PersonFormFields'

import axios from '@/plugins/axios'

export default {
    components: {
        BForm,
        BButton,
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
        onSubmit(evt) {
            evt.preventDefault()
            this.busy = true
            axios.put(this.apiUrl, this.person)
                .then(response => {
                    var data = response.data
                    showSnackbar(data.message);

                    const filter = this.person.police_no ? this.person.police_no : `${this.person.name} ${this.person.family_name}`
                    rememberedFilter.set(filter)

                    window.location.href = this.redirectUrl
                })
                .catch(err => handleAjaxError(err))
                .then(() => this.busy = false)
        }
    }
}
</script>