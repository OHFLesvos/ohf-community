<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card :title="title" body-class="pb-2" footer-class="d-flex justify-content-between align-items-start" class="mb-3">
                <b-form-row>
                    <!-- Salutation -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Salutation')"
                            vid="salutation"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Salutation')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.salutation"
                                    autocomplete="off"
                                    list="salutation-list"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                            <b-form-datalist id="salutation-list" :options="salutations" />
                        </validation-provider>
                    </b-col>

                    <!-- First name -->
                    <b-col md>
                        <validation-provider
                            :name="$t('First Name')"
                            vid="first_name"
                            :rules="{ required_without: 'company' }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('First Name')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.first_name"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Last name -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Last Name')"
                            vid="last_name"
                            :rules="{ required_without: 'company' }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Last Name')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.last_name"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Company -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Company')"
                            vid="company"
                            :rules="{ required_without: 'last_name' }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Company')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.company"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>
                <b-form-row>

                    <!-- Street -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Street')"
                            vid="street"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Street')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.street"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- ZIP -->
                    <b-col md="2" lg="1">
                        <validation-provider
                            :name="$t('ZIP')"
                            vid="zip"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('ZIP')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.zip"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- City -->
                    <b-col md>
                        <validation-provider
                            :name="$t('City')"
                            vid="city"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('City')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.city"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Country -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Country')"
                            vid="country"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Country')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.country_name"
                                    autocomplete="off"
                                    list="country-list"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                            <b-form-datalist id="country-list" :options="countries" />
                        </validation-provider>
                    </b-col>

                </b-form-row>
                <b-form-row>

                    <!-- E-Mail -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Email address')"
                            vid="email"
                            :rules="{ email: true }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Email address')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Phone -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Phone')"
                            vid="phone"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Phone')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.phone"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Language -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Correspondence language')"
                            vid="language"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Correspondence language')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.language"
                                    autocomplete="off"
                                    list="language-list"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                            <b-form-datalist id="language-list" :options="languages" />
                        </validation-provider>
                    </b-col>

                </b-form-row>

                <template #footer>
                    <span>
                        <!-- Submit -->
                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="disabled"
                        >
                            <font-awesome-icon icon="check" />
                            {{ donor ? $t('Update') : $t('Add') }}
                        </b-button>

                        <!-- Cancel -->
                        <b-button
                            variant="link"
                            :disabled="disabled"
                            @click="$emit('cancel')"
                        >
                            {{ $t('Cancel') }}
                        </b-button>
                    </span>

                    <!-- Delete -->
                    <b-button
                        v-if="donor && donor.can_delete"
                        variant="link"
                        :disabled="disabled"
                        class="text-danger"
                        @click="onDelete"
                    >
                        {{ $t('Delete') }}
                    </b-button>

                </template>
            </b-card>
        </b-form>
    </validation-observer>
</template>

<script>
import commonApi from '@/api/common'
import donorsApi from '@/api/fundraising/donors'
import formValidationMixin from "@/mixins/formValidationMixin";
export default {
    mixins: [formValidationMixin],
    props: {
        donor: {
            type: Object,
            required: false
        },
        title: {
            required: false,
            default: undefined
        },
        disabled: Boolean
    },
    data () {
        return {
            form: this.donor ? {
                salutation: this.donor.salutation,
                first_name: this.donor.first_name,
                last_name: this.donor.last_name,
                company: this.donor.company,
                street: this.donor.street,
                zip: this.donor.zip,
                city: this.donor.city,
                country_name: this.donor.country_name,
                email: this.donor.email,
                phone: this.donor.phone,
                language: this.donor.language
            } : {
                salutation: null,
                first_name: null,
                last_name: null,
                company: null,
                street: null,
                zip: null,
                city: null,
                country_name: null,
                email: null,
                phone: null,
                language: null
            },
            salutations: [],
            countries: [],
            languages: []
        }
    },
    created () {
        this.fetchSalutations()
        this.fetchCountries()
        this.fetchLanguages()
    },
    methods: {
        async fetchSalutations () {
            let data = await donorsApi.listSalutations()
            this.salutations = data.data
        },
        async fetchCountries () {
            let data = await commonApi.listLocalizedCountries()
            this.countries = Object.values(data)
        },
        async fetchLanguages () {
            let data = await commonApi.listLocalizedLanguages()
            this.languages = Object.values(data)
        },
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('Do you really want to delete this donor?'))) {
                this.$emit('delete')
            }
        }
    }
}
</script>
