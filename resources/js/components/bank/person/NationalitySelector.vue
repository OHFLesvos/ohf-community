<template>
    <span class="form-inline d-inline">
        <font-awesome-icon
            v-if="busy"
            icon="spinner"
            spin
        />
        <template v-else-if="nationality != null">
            {{ nationality }}
        </template>
        <template v-else-if="allowUpdate">
            <template v-if="form">
                <b-form-input
                    ref="input"
                    v-model="newNationality"
                    size="sm"
                    :placeholder="$t('people.choose_nationality')"
                    list="country-list"
                    @keydown.enter="setNationality"
                    @keydown.esc="form = false"
                />
                <b-form-datalist id="country-list" :options="countries"/>
                <b-button
                    variant="primary"
                    size="sm"
                    @click="setNationality"
                >
                    <font-awesome-icon icon="check"/>
                </b-button>
                <b-button
                    variant="secondary"
                    size="sm"
                    @click="form = false"
                >
                    <font-awesome-icon icon="times"/>
                </b-button>
            </template>
            <b-button
                v-else
                variant="warning"
                size="sm"
                title="Set nationality"
                :disabled="disabled"
                @click="form = true"
            >
                <font-awesome-icon icon="globe"/>
            </b-button>
        </template>
    </span>
</template>

<script>
import showSnackbar from '@/snackbar'
import { BButton, BFormInput, BFormDatalist } from 'bootstrap-vue'
import peopleApi from '@/api/people'
import commonApi from '@/api/common'
export default {
    components: {
        BButton,
        BFormInput,
        BFormDatalist
    },
    props: {
        person: {
            required: true
        },
        allowUpdate: Boolean,
        disabled: Boolean
    },
    data () {
        return {
            busy: false,
            form: false,
            nationality: this.person.nationality != null && this.person.nationality.length > 0 ? this.person.nationality : null,
            newNationality: null,
            countries: []
        }
    },
    watch: {
        form (val, oldVal) {
            if (val && !oldVal) {
                if (this.countries.length == 0) {
                    this.loadCountries()
                }
                this.$nextTick(() => this.$refs.input.focus())
            }
            if (!val && oldVal) {
                this.newNationality = null
            }
        }
    },
    methods: {
        async loadCountries () {
            let data = await commonApi.listCountries('en')
            this.countries = Object.values(data)
        },
        async setNationality() {
            if (this.newNationality == null || this.newNationality.length == 0) {
                this.$refs.input.select()
                return
            }
            this.busy = true
            try {
                let data = await peopleApi.updateNationality(this.person.id, this.newNationality)
                this.nationality = this.newNationality
                this.form = false
                showSnackbar(data.message)
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
                this.busy = false // Important to set busy to false before nextTick
                this.$nextTick(() => this.$refs.input.select())
            }
            this.busy = false
        }
    }
}
</script>
