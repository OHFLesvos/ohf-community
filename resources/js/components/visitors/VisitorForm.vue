<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }">
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-form-row>
                <b-col sm>
                    <validation-provider
                        :name="$t('Name')"
                        vid="name"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Name')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="formData.name"
                                trim
                                required
                                autofocus
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="disabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <b-col sm>
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
                        >
                            <b-form-input
                                v-model="formData.id_number"
                                trim
                                required
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="disabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <b-form-row>
                <b-col cols="auto" class="pr-3">
                    <b-form-group :label="$t('Gender')">
                        <b-form-radio-group
                            v-model="formData.gender"
                            :options="genders"
                            stacked
                        />
                    </b-form-group>
                </b-col>

                <b-col sm>
                    <validation-provider
                        :name="$t('Nationality')"
                        vid="nationality"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Nationality')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="formData.nationality"
                                trim
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="disabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <validation-provider
                        :name="$t('Living situation')"
                        vid="living_situation"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Living situation')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="formData.living_situation"
                                trim
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                                :disabled="disabled"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>
            <p>
                <b-button variant="primary" type="submit" :disabled="disabled">
                    <font-awesome-icon icon="check" />
                    {{ $t("Register") }}
                </b-button>
            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import formInputMixin from "@/mixins/formInputMixin";
export default {
    props: {
        disabled: Boolean,
    },
    mixins: [formInputMixin],
    data() {
        return {
            formData: {
                name: "",
                id_number: "",
                gender: null,
                living_situation: "",
                nationality: "",
            },
            genders: [
                { value: "male", text: this.$t("Male") },
                { value: "female", text: this.$t("Female") },
                { value: "other", text: this.$t("Other") },
            ],
        };
    },
    methods: {
        submit() {
            this.$refs.observer.validate().then((valid) => {
                if (valid) {
                    this.onSubmit();
                }
            });
        },
        onSubmit() {
            this.$emit("submit", this.formData);
        },
    },
};
</script>
