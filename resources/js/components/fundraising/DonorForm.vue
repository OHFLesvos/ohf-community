<template>
    <validation-observer
        ref="observer"
        v-slot="{ handleSubmit }"
        slim
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">

            <b-form-row>

                <!-- Salutation -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.salutation')"
                        vid="salutation"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.salutation')"
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
                        :name="$t('app.first_name')"
                        vid="first_name"
                        :rules="{ required_without: 'company' }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.first_name')"
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
                        :name="$t('app.last_name')"
                        vid="last_name"
                        :rules="{ required_without: 'company' }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.last_name')"
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
                        :name="$t('app.company')"
                        vid="company"
                        :rules="{ required_without: 'last_name' }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.company')"
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
                        :name="$t('app.street')"
                        vid="street"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.street')"
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
                        :name="$t('app.zip')"
                        vid="zip"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.zip')"
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
                        :name="$t('app.city')"
                        vid="city"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.city')"
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
                        :name="$t('app.country')"
                        vid="country"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.country')"
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
                        :name="$t('app.email')"
                        vid="email"
                        :rules="{ email: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.email')"
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
                        :name="$t('app.phone')"
                        vid="phone"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.phone')"
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
                        :name="$t('app.correspondence_language')"
                        vid="language"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.correspondence_language')"
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

            <p class="d-flex justify-content-between align-items-start">
                <span>
                    <!-- Submit -->
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="disabled"
                    >
                        <font-awesome-icon icon="check" />
                        {{ donor ? $t('app.update') : $t('app.add') }}
                    </b-button>

                    <!-- Cancel -->
                    <b-button
                        variant="link"
                        :disabled="disabled"
                        @click="$emit('cancel')"
                    >
                        {{ $t('app.cancel') }}
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
                    {{ $t('app.delete') }}
                </b-button>

            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import commonApi from '@/api/common'
import donorsApi from '@/api/fundraising/donors'
export default {
    props: {
        donor: {
            type: Object,
            required: false
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
            let data = await commonApi.listCountries()
            this.countries = Object.values(data)
        },
        async fetchLanguages () {
            let data = await commonApi.listLanguages()
            this.languages = Object.values(data)
        },
        getValidationState ({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('app.confirm_delete_donor'))) {
                this.$emit('delete')
            }
        }
    }
}
</script>
