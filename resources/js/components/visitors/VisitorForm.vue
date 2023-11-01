<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }">
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card>
                <b-card-title v-if="header">{{ header }}</b-card-title>
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
                            :rules="{ required: false }"
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
                                    :disabled="disabled"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <b-col sm>
                        <validation-provider
                            :name="$t('Date of birth')"
                            vid="date_of_birth"
                            :rules="{ required: false, date: true }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Date of birth')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <DateOfBirthInput
                                    v-model="formData.date_of_birth"
                                    :disabled="disabled"
                                />
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
                <b-form-row>
                    <b-col sm>
                        <b-form-group :label="$t('Liability form signed')">
                            <b-form-datepicker
                                v-model="formData.liability_form_signed"
                                today-button
                                reset-button
                                :max="today">
                            </b-form-datepicker>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group :label="$t('Parental consent')"  v-if="age < 18">
                            <b-form-checkbox v-model="formData.parental_consent_given">
                                {{ $t("Parental consent given") }}
                            </b-form-checkbox>
                        </b-form-group>
                    </b-col>
                </b-form-row>
                <b-form-row>
                    <b-col>
                        <b-form-group
                            :label="$t('Remarks')"
                        >
                            <b-form-input
                                v-model="formData.remarks"
                                trim
                                autocomplete="off"
                                :disabled="disabled"
                            />
                        </b-form-group>
                    </b-col>
                </b-form-row>
                <b-form-row>
                    <b-col>
                        <b-form-group :label="$t('Parents')">
                            <ul>
                                <li v-for="(parent, index) in formData.parents" :key="parent.id" class="p3">
                                    {{ parent.name }}
                                    <b-button size="sm" class="text-danger float-right" variant="link" @click="removeParent(index)">Remove</b-button>
                                </li>
                                <p v-if="formData.parents.length == 0">No parent registered.</p>
                            </ul>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group :label="$t('Children')">
                            <ul>
                                <li v-for="(child, index) in formData.children" :key="child.id" class="p-1 align-items-center">
                                    {{ child.name }}
                                    <b-button size="sm" class="text-danger float-right" variant="link" @click="removeChild(index)">Remove</b-button>
                                </li>
                                <p v-if="formData.children.length == 0">No children registered.</p>
                            </ul>
                        </b-form-group>
                    </b-col>
                </b-form-row>
                <b-form-group>
                    <b-form-input
                        v-model.trim="familySearch"
                        type="search"
                        debounce="500"
                        :placeholder="
                            $t('Search for child or parent by name, ID number or date of birth…')
                        "
                        autofocus
                        autocomplete="off"
                    />
                </b-form-group>
                <template v-if="familySearched">
                    <b-card
                        v-for="visitor in visitors"
                        :key="visitor.id"
                        class="mb-3 shadow-sm"
                    >
                        <VisitorDetails :value="visitor" />
                        <template #footer>
                            <b-button
                                variant="primary"
                                @click="addParent(visitor)"
                            >
                                {{ $t("Add Parent") }}
                            </b-button>
                            <b-button
                                class="float-right"
                                variant="primary"
                                @click="addChild(visitor)"
                                >
                                {{ $t("Add Child") }}
                            </b-button>
                        </template>
                    </b-card>
                    <p>Showing top 5 results.</p>
                </template>
                <template #footer>
                    <div class="d-flex justify-content-between align-items-start">
                        <span>
                            <b-button
                                variant="primary"
                                type="submit"
                                :disabled="disabled"
                            >
                                <font-awesome-icon icon="check" />
                                {{ value ? $t("Update") : $t("Register") }}
                            </b-button>
                            <b-button variant="link" @click="$emit('cancel')">{{
                                $t("Cancel")
                            }}</b-button>
                        </span>

                        <b-button
                            v-if="value"
                            variant="link"
                            :disabled="disabled"
                            class="text-danger"
                            @click="onDelete"
                        >
                            {{ $t("Delete") }}
                        </b-button>
                    </div>
                </template>
            </b-card>
        </b-form>
    </validation-observer>
</template>

<script>
import visitorsApi from "@/api/visitors";
import VisitorDetails from "@/components/visitors/VisitorDetails.vue";
import formValidationMixin from "@/mixins/formValidationMixin";
import { mapState } from "vuex";
import { calculateAge } from "@/utils";
import moment from "moment";
import DateOfBirthInput from "@/components/common/DateOfBirthInput.vue";
export default {
    mixins: [formValidationMixin],
    components: {
        DateOfBirthInput,
        VisitorDetails,
    },
    props: {
        value: {
            required: false,
        },
        header: {
            required: false,
            default: undefined,
        },
        disabled: Boolean,
    },
    data() {
        const search = this.$route.query.search;
        const searchType = this.detectValueType(search);
        return {
            formData: this.value ?? {
                name: search && searchType == "string" ? search : "",
                id_number: search && searchType == "number" ? search : "",
                gender: null,
                date_of_birth: search && searchType == "date" ? search : "",
                nationality: "",
                living_situation: "",
                remarks: "",
                liability_form_signed: null,
                parental_consent_given: false,
                parents: [],
                children: [],
            },
            genders: [
                { value: "male", text: this.$t("male") },
                { value: "female", text: this.$t("female") },
                { value: "other", text: this.$t("other") },
            ],
            familySearch: "",
            familySearched: false,
            visitors: [],
        };
    },
    computed: {
        ...mapState(["settings"]),
        nationalities() {
            return ["", ...this.settings["visitors.nationalities"]];
        },
        livingSituations() {
            return ["", ...this.settings["visitors.living_situations"]];
        },
        today() {
            return moment().format(moment.HTML5_FMT.DATE);
        },
        age() {
            return calculateAge(this.formData.date_of_birth)
        },
    },
    watch: {
        familySearch: {
            immediate: true,
            handler(value) {
                this.familySearched = false;
                if (value.length > 0) {
                    this.searchVisitors();
                }
                else {
                    this.visitors = [];
                }
            }
        },
    },
    mounted() {
        if (!this.value) {
            this.$refs.nameInput.focus();
        }
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
        detectValueType(value) {
            if (moment(value, moment.DATE, true).isValid()) {
                return "date";
            }
            if (/\d+/.test(value)) {
                return "number";
            }
            return "string";
        },
        onDelete() {
            if (confirm(this.$t("Really delete this visitor?"))) {
                this.$emit("delete");
            }
        },
        removeChild(index) {
            this.formData.children.splice(index, 1);
        },
        removeParent(index) {
            this.formData.parents.splice(index, 1);
        },
        addChild(visitor) {
            this.formData.children.push({ name: visitor.name, id: visitor.id });
            this.familySearch = "";
        },
        addParent(visitor) {
            this.formData.parents.push({ name: visitor.name, id: visitor.id });
            this.familySearch = "";
        },
        async searchVisitors() {
            this.errorText = null;
            try {
                let data = await visitorsApi.list({
                    filter: this.familySearch,
                    pageSize: 5,
                });
                this.visitors = data.data;
                this.totalRows = data.meta.total;
                this.familySearched = true;
            } catch (ex) {
                this.errorText = ex;
            }
        },
    },
};
</script>
