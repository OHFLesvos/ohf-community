<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-form-row>
                <!-- Name -->
                <b-col md>
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
                                v-model="form.name"
                                autocomplete="off"
                                reqired
                                :autofocus="!budget"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <b-form-row>

                <!-- Agreed amount -->
                <b-col md="3">
                    <validation-provider
                        :name="$t('Agreed amount')"
                        vid="agreed_amount"
                        :rules="{ required: true, decimal: true, min_value: 0 }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Agreed amount')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                            :description="
                                $t('Write decimal point as comma (,)')
                            "
                        >
                            <b-input-group
                                :append="
                                    settings['accounting.transactions.currency']
                                "
                            >
                                <b-form-input
                                    v-model="form.agreed_amount"
                                    autocomplete="off"
                                    type="number"
                                    required
                                    step=".01"
                                    min="0"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                />
                            </b-input-group>
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Initial amount -->
                <b-col md="3">
                    <validation-provider
                        :name="$t('Initial amount')"
                        vid="initial_amount"
                        :rules="{ required: true, decimal: true, min_value: 0 }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Initial amount')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                            :description="
                                $t('Write decimal point as comma (,)')
                            "
                        >
                            <b-input-group
                                :append="
                                    settings['accounting.transactions.currency']
                                "
                            >
                                <b-form-input
                                    v-model="form.initial_amount"
                                    autocomplete="off"
                                    type="number"
                                    required
                                    step=".01"
                                    min="0"
                                    :state="
                                        getValidationState(validationContext)
                                    "
                                />
                            </b-input-group>
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Currency -->
                <b-col md>
                    <validation-provider
                        :name="$t('Currency')"
                        vid="currency"
                        :rules="{ required: false }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Currency')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-select
                                v-model="form.currency"
                                :options="currencies"
                                :state="getValidationState(validationContext)"
                                :disabled="!isLoaded"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <b-form-row>
                <!-- Description -->
                <b-col md>
                    <validation-provider
                        :name="$t('Description')"
                        vid="description"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Description')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-textarea
                                v-model="form.description"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <b-form-row>
                <!-- Donor -->
                <b-col sm>
                    <validation-provider
                        :name="$t('Donor')"
                        vid="donor_id"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Donor')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.donor_id"
                                :options="donors"
                                :state="getValidationState(validationContext)"
                                :disabled="!isLoaded"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Closing date -->
                <b-col sm>
                    <validation-provider
                        :name="$t('Closing date')"
                        vid="closed_at"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Closing date')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-datepicker
                                v-model="form.closed_at"
                                autocomplete="off"
                                reset-button
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <p>
                <b-form-checkbox v-model="form.is_completed">
                    {{ $t("Completed") }}
                </b-form-checkbox>
            </p>

            <p class="d-flex justify-content-between align-items-start">
                <span>
                    <!-- Submit -->
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="disabled || !isLoaded"
                    >
                        <font-awesome-icon icon="check" />
                        {{ budget ? $t("Update") : $t("Add") }}
                    </b-button>

                    <!-- Cancel -->
                    <b-button
                        variant="link"
                        :disabled="disabled"
                        @click="$emit('cancel')"
                    >
                        {{ $t("Cancel") }}
                    </b-button>
                </span>

                <!-- Delete -->
                <b-button
                    v-if="budget && budget.can_delete"
                    variant="link"
                    :disabled="disabled"
                    class="text-danger"
                    @click="onDelete"
                >
                    {{ $t("Delete") }}
                </b-button>
            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import commonApi from "@/api/common";
import donorsApi from "@/api/fundraising/donors";
import { mapState } from "vuex";
export default {
    props: {
        budget: {
            type: Object,
            required: false
        },
        disabled: Boolean
    },
    data() {
        return {
            form: this.budget
                ? {
                      name: this.budget.name,
                      agreed_amount: this.budget.agreed_amount,
                      initial_amount: this.budget.initial_amount,
                      currency: this.budget.currency,
                      description: this.budget.description,
                      donor_id: this.budget.donor_id,
                      closed_at: this.budget.closed_at,
                      is_completed: this.budget.is_completed
                  }
                : {
                      name: null,
                      agreed_amount: 0,
                      initial_amount: 0,
                      currency: null,
                      description: null,
                      donor_id: null,
                      closed_at: null,
                      is_completed: false
                  },
            donors: [],
            currencies: [],
            isLoaded: false
        };
    },
    computed: {
        ...mapState(["settings"])
    },
    async created() {
        if (this.can("view-fundraising-entities")) {
            await this.fetchDonors();
        }
        await this.fetchCurrencies();
        this.isLoaded = true
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onSubmit() {
            this.$emit("submit", this.form);
        },
        onDelete() {
            if (confirm(this.$t("Really delete this budget?"))) {
                this.$emit("delete");
            }
        },
        async fetchDonors() {
            let data = await donorsApi.names();
            let donors = [
                {
                    value: null,
                    text: `- ${this.$t("No donor")} -`
                }
            ];
            donors.push(
                ...data.map(donor => {
                    return {
                        text: donor.name,
                        value: donor.id
                    };
                })
            );
            this.donors = donors;
        },
        async fetchCurrencies() {
            let data = await commonApi.listCurrencies();
            let items = [
                {
                    value: null,
                    text: `- ${this.$t("Default")} -`,
                },
            ];
            items.push(
                ...Object.entries(data).map((e) => {
                    return {
                        text: e[1],
                        value: e[0],
                    };
                })
            );
            this.currencies = items;
        },
    }
};
</script>
