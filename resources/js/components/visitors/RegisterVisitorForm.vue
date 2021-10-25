<template>
    <validation-observer
        ref="observer"
        v-slot="{ handleSubmit }"
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-form-row>
                <b-col
                  v-if="formData.type == 'visitor' || formData.type == 'participant'"
                  sm
                >
                    <validation-provider
                        :name="$t('ID Number')"
                        vid="id_number"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('ID Number')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                            class="required-marker"
                        >
                            <b-form-input
                                ref="idNumberInput"
                                v-model="formData.id_number"
                                trim
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="isDisabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <validation-provider
                        :name="$t('First Name')"
                        vid="first_name"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('First Name')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="formData.first_name"
                                trim
                                ref="firstNameInput"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="isDisabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
                <b-col sm>
                    <validation-provider
                        :name="$t('Last Name')"
                        vid="last_name"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Last Name')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="formData.last_name"
                                trim
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="isDisabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col cols="auto" class="pr-3">
                    <b-form-group :label="$t('Type')">
                        <b-form-radio-group
                            v-model="formData.type"
                            :options="types"
                            stacked
                            @change="changeType"
                        />
                    </b-form-group>
                </b-col>
                <b-col
                  v-if="formData.type == 'visitor'"
                  sm
                >
                    <validation-provider
                        :name="$t('Place of residence')"
                        vid="place_of_residence"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Place of residence')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="formData.place_of_residence"
                                trim
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="isDisabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
                <b-col
                  v-if="formData.type == 'participant' || formData.type == 'external'"
                  sm
                >
                    <validation-provider
                        :name="$t('Organization')"
                        vid="organization"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Organization')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                ref="organizationInput"
                                v-model="formData.organization"
                                trim
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="isDisabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>
            <p>
                <b-button
                    variant="primary"
                    type="submit"
                    :disabled="isDisabled"
                >
                    <font-awesome-icon icon="check" />
                    {{ $t('Register') }}
                </b-button>
                <b-button
                    variant="secondary"
                    type="button"
                    :disabled="isDisabled"
                    @click="reset"
                >
                    <font-awesome-icon icon="eraser" />
                    {{ $t('Reset') }}
                </b-button>
            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import formInputMixin from '@/mixins/formInputMixin'
export default {
    props: {
        disabled: Boolean,
    },
    mixins: [ formInputMixin ],
    data () {
        return {
            formData: this.initialFormData(),
            types: [
                { value: 'visitor', text: this.$t('Visitor OHF') },
                { value: 'participant', text: this.$t('Visitor Partner') },
                { value: 'external', text: this.$t('Other') },
            ]
        }
    },
    computed: {
        isDisabled () {
            return this.disabled
        }
    },
    watch: {
        disabled (val, oldVal) {
            if (!val && oldVal) {
                this.$nextTick(() => this.focus())
            }
        }
    },
    mounted () {
        this.focus()
    },
    methods: {
        submit () {
            this.$refs.observer.validate()
                .then(valid => {
                    if (valid) {
                        this.onSubmit()
                    }
                })
        },
        onSubmit () {
            if (this.formData.type == 'visitor') {
                this.formData.organization = ''
            } else if (this.formData.type == 'participant') {
                this.formData.place_of_residence = ''
            } else if (this.formData.type == 'external') {
                this.formData.id_number = ''
                this.formData.place_of_residence = ''
            }
            this.$emit('submit', this.formData)
        },
        reset () {
            this.formData = this.initialFormData()
            this.$refs.observer.reset()
            this.focus()
        },
        initialFormData () {
            return {
                first_name: '',
                last_name: '',
                type: 'visitor',
                id_number: '',
                place_of_residence: '',
                organization: ''
            }
        },
        focus () {
            this.$refs.idNumberInput.focus()
        },
        changeType (value) {
            if (value == 'external') {
                this.$nextTick(() => this.$refs.organizationInput.focus())
            } else if (value == 'participant') {
                this.$nextTick(() => this.$refs.organizationInput.focus())
            } else {
                this.$nextTick(() => this.$refs.idNumberInput.focus())
            }
        }
    }
}
</script>

<style>
    .required-marker legend:after {
        content: '*';
        color: red;
        margin-left: 3px;
    }
</style>
