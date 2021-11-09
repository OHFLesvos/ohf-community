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
                                ref="nameInput"
                                v-model="formData.name"
                                trim
                                required
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
                <b-col sm class="pr-3">
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
                                v-model="formData.gender"
                                :options="genders"
                                stacked
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <b-col sm>
                    <validation-provider
                        :name="$t('Date of birth')"
                        vid="date_of_birth"
                        :rules="{ required: true, date: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Date of birth')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-input-group :append="age">
                                <b-form-input
                                    v-model="formData.date_of_birth"
                                    trim
                                    autocomplete="off"
                                    required
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                    :disabled="disabled"
                                    placeholder="YYYY-MM-DD"
                                />
                            </b-input-group>
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <b-form-group :label="$t('Nationality')">
                        <b-form-select
                            v-model="formData.nationality"
                            autocomplete="off"
                            :disabled="disabled"
                            :options="nationalities"
                        />
                    </b-form-group>
                </b-col>

                <b-col sm>
                    <b-form-group :label="$t('Living situation')">
                        <b-form-select
                            v-model="formData.living_situation"
                            autocomplete="off"
                            :disabled="disabled"
                            :options="livingSituations"
                    /></b-form-group>
                </b-col>
            </b-form-row>
            <div>
                <b-button variant="primary" type="submit" :disabled="disabled">
                    <font-awesome-icon icon="check" />
                    {{ $t("Register") }}
                </b-button>
                <b-button variant="link" @click="$emit('cancel')">{{
                    $t("Cancel")
                }}</b-button>
            </div>
        </b-form>
    </validation-observer>
</template>

<script>
import formInputMixin from "@/mixins/formInputMixin";
import { mapState } from "vuex";
import moment from "moment";
export default {
    props: {
        disabled: Boolean
    },
    mixins: [formInputMixin],
    data() {
        const search = this.$route.query.search;
        const searchType = this.detectValueType(search);
        return {
            formData: {
                name: search && searchType == "string" ? search : "",
                id_number: search && searchType == "number" ? search : "",
                gender: null,
                date_of_birth: search && searchType == "date" ? search : "",
                nationality: "",
                living_situation: ""
            },
            genders: [
                { value: "male", text: this.$t("Male") },
                { value: "female", text: this.$t("Female") },
                { value: "other", text: this.$t("Other") }
            ]
        };
    },
    computed: {
        ...mapState(["settings"]),
        nationalities() {
            return ["", ...this.settings["visitors.nationalities"]];
        },
        livingSituations() {
            return ["", ...this.settings["visitors.living_situation"]];
        },
        age() {
            if (this.formData.date_of_birth) {
                let date = moment(
                    this.formData.date_of_birth,
                    moment.HTML5_FMT.DATE,
                    true
                );
                if (date.isValid()) {
                    return ""+moment().diff(date, "years");
                }
            }
            return undefined;
        }
    },
    mounted() {
        this.$refs.nameInput.focus();
    },
    methods: {
        submit() {
            this.$refs.observer.validate().then(valid => {
                if (valid) {
                    this.onSubmit();
                }
            });
        },
        onSubmit() {
            this.$emit("submit", this.formData);
        },
        detectValueType(value) {
            if (moment(value, moment.DATE, true).isValid()) {
                return "date";
            }
            if (/\d+/.test(value)) {
                return "number";
            }
            return "string";
        }
    }
};
</script>
