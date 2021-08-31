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

                <!-- Amount -->
                <b-col md="3">
                    <validation-provider
                        :name="$t('Amount')"
                        vid="amount"
                        :rules="{ required: true, decimal: true, min_value: 0 }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Amount')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                            :description="
                                $t('Write decimal point as comma (,)')
                            "
                        >
                            <b-form-input
                                v-model="form.amount"
                                autocomplete="off"
                                type="number"
                                required
                                step=".01"
                                min="0"
                                :state="getValidationState(validationContext)"
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
                        :disabled="disabled"
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
import { can } from "@/plugins/laravel";
import donorsApi from "@/api/fundraising/donors";
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
                      amount: this.budget.amount,
                      description: this.budget.description,
                      donor_id: this.budget.donor_id,
                      closed_at: this.budget.closed_at,
                      is_completed: this.budget.is_completed
                  }
                : {
                      name: null,
                      amount: 0,
                      description: null,
                      donor_id: null,
                      closed_at: null,
                      is_completed: false
                  },
            donors: []
        };
    },
    created() {
        if (this.can("view-fundraising-entities")) {
            this.fetchDonors();
        }
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
        can
    }
};
</script>
