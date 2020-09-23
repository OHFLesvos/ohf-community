<template>
    <validation-observer
        ref="observer"
        v-slot="{ handleSubmit }"
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-form-row>
                <b-col sm>
                    <validation-provider
                        :name="$t('app.first_name')"
                        vid="first_name"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.first_name')"
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
                        :name="$t('app.last_name')"
                        vid="last_name"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.last_name')"
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
                <b-col sm="4">
                    <validation-provider
                        :name="$t('app.id_number')"
                        vid="id_number"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.id_number')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="formData.id_number"
                                trim
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="isDisabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
                <b-col sm="8">
                    <validation-provider
                        :name="$t('app.place_of_residence')"
                        vid="place_of_residence"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.place_of_residence')"
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
            </b-form-row>
            <p>
                <b-button
                    variant="primary"
                    type="submit"
                    :disabled="isDisabled"
                >
                    <font-awesome-icon icon="check" />
                    {{ $t('app.register') }}
                </b-button>
                <b-button
                    variant="secondary"
                    type="button"
                    :disabled="isDisabled"
                    @click="reset"
                >
                    <font-awesome-icon icon="eraser" />
                    {{ $t('app.reset') }}
                </b-button>
                <b-button
                    variant="secondary"
                    type="button"
                    :disabled="isDisabled"
                    @click="cancel"
                >
                    <font-awesome-icon icon="times" />
                    {{ $t('app.cancel') }}
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
            formData: {
                first_name: '',
                last_name: '',
                id_number: '',
                place_of_residence: '',
            }
        }
    },
    computed: {
        isDisabled () {
            return this.disabled
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
            this.$emit('submit', this.formData)
        },
        reset () {
            this.formData = {
                first_name: '',
                last_name: '',
                id_number: '',
                place_of_residence: '',
            }
            this.$refs.observer.reset()
            this.focus()
        },
        cancel () {
            this.$emit('cancel')
        },
        focus () {
            this.$refs.firstNameInput.focus()
        }
    }
}
</script>
