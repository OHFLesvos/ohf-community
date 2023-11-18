<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card :title="title" body-class="pb-2" footer-class="d-flex justify-content-between align-items-start" class="mb-3">

                <b-card-sub-title class="py-3">{{ $t('General') }}</b-card-sub-title>

                <b-form-row>

                    <!-- First name -->
                    <b-col md>
                        <validation-provider
                            :name="$t('First Name')"
                            vid="first_name"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('First Name')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.first_name"
                                    required
                                    autocomplete="off"
                                    :autofocus="cmtyvol == null"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Last name -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Family Name')"
                            vid="family_name"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Family Name')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.family_name"
                                    required
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>
                <b-form-row>

                    <!-- Nickname -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Nickname')"
                            vid="nickname"
                            :rules="{ }"
                            v-slot="validationContext"
                            >
                            <b-form-group
                                :label="$t('Nickname')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.nickname"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Nationality -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Nationality')"
                            vid="nationality"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Nationality')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.nationality"
                                    autocomplete="off"
                                    list="country-list"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                            <b-form-datalist id="country-list" :options="countryList" />
                        </validation-provider>
                    </b-col>

                </b-form-row>
                <b-form-row>

                    <!-- Gender -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Gender')"
                            vid="gender"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Gender')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-radio-group
                                    v-model="form.gender"
                                    :options="genders"
                                    stacked
                                    required
                                    :disabled="disabled"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Date of birth -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Date of birth')"
                            vid="date_of_birth"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Date of birth')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.date_of_birth"
                                    required
                                    autocomplete="off"
                                    placeholder="YYYY-MM-DD"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>
                <b-form-row>

                    <!-- Police Number -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Police Number')"
                            vid="police_no"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Police Number')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.police_no"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Languages -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Languages')"
                            vid="languages"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Languages')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                                :description="$t('Separate by comma')"
                            >
                                <b-form-input
                                    v-model="form.languages"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>

                <validation-provider
                    :name="$t('Notes')"
                    vid="notes"
                    :rules="{ }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('Notes')"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                    >
                        <b-form-textarea
                            v-model="form.notes"
                            autocomplete="off"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>

                <b-card-sub-title class="py-3">{{ $t('Reachability') }}</b-card-sub-title>

                <b-form-row>

                    <!-- Local Phone -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Local Phone')"
                            vid="local_phone"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Local Phone')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.local_phone"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Other Phone -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Other Phone')"
                            vid="other_phone"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Other Phone')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.other_phone"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>
                <b-form-row>

                    <!-- WhatsApp -->
                    <b-col md>
                        <validation-provider
                            :name="$t('WhatsApp')"
                            vid="whatsapp"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('WhatsApp')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.whatsapp"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Skype -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Skype')"
                            vid="skype"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Skype')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.skype"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>

                <!-- E-Mail -->
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

                <b-form-row>

                    <!-- Residence -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Residence')"
                            vid="residence"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Residence')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-textarea
                                    v-model="form.residence"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Pickup location -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Pickup location')"
                            vid="pickup_location"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Pickup location')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.pickup_location"
                                    autocomplete="off"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
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
                            {{ cmtyvol ? $t('Update') : $t('Add') }}
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
                        v-if="cmtyvol && cmtyvol.can_delete"
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
import formValidationMixin from "@/mixins/formValidationMixin";
export default {
    mixins: [formValidationMixin],
    props: {
        cmtyvol: {
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
            form: this.cmtyvol ? {
                first_name: this.cmtyvol.first_name,
                family_name: this.cmtyvol.family_name,
                nickname: this.cmtyvol.nickname,
                nationality: this.cmtyvol.nationality,
                gender: this.cmtyvol.gender,
                date_of_birth: this.cmtyvol.date_of_birth,
                police_no: this.cmtyvol.police_no,
                languages: this.cmtyvol.languages?.join(', '),
                notes: this.cmtyvol.notes,
                local_phone: this.cmtyvol.local_phone,
                other_phone: this.cmtyvol.other_phone,
                whatsapp: this.cmtyvol.whatsapp,
                skype: this.cmtyvol.local_phone,
                email: this.cmtyvol.email,
                residence: this.cmtyvol.residence,
                pickup_location: this.cmtyvol.pickup_location,
            } : {
                first_name: null,
                family_name: null,
                nickname: null,
                nationality: null,
                gender: null,
                date_of_birth: null,
                police_no: null,
                languages: null,
                notes: null,
                local_phone: null,
                other_phone: null,
                whatsapp: null,
                skype: null,
                email: null,
                residence: null,
                pickup_location: null,
            },
            genders: [
                { value: null, text: this.$t("Unspecified") },
                { value: "m", text: this.$t("male") },
                { value: "f", text: this.$t("female") },
            ]
        }
    },
    computed: {
        countryList() {
            return this.$store.getters['country/getCountryList'];
        },
    },
    mounted() {
        this.$store.dispatch('country/fetchCountryList');
    },
    methods: {
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('Really delete this community volunteer?'))) {
                this.$emit('delete')
            }
        }
    }
}
</script>
