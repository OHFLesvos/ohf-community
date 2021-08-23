<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-form-row>
                <!-- Receipt No. -->
                <b-col sm="auto">
                    <validation-provider
                        :name="$t('Receipt No.')"
                        vid="receipt_no"
                        :rules="{ required: true, integer: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Receipt No.')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.receipt_no"
                                autocomplete="off"
                                type="number"
                                required
                                step="1"
                                min="1"
                                :autofocus="!transaction"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Date -->
                <b-col sm="auto">
                    <validation-provider
                        :name="$t('Date')"
                        vid="date"
                        :rules="{ required: true, date: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Date')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.date"
                                autocomplete="off"
                                type="date"
                                required
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <b-form-row>
                <!-- Amount -->
                <b-col sm>
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
                            :description="$t('Write decimal point as comma (,)')"
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

                <b-col sm>
                    <validation-provider
                        :name="$t('Transaction fees')"
                        vid="fees"
                        :rules="{ decimal: true, min_value: 0 }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Transaction fees')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                            :description="$t('Write decimal point as comma (,)')"
                        >
                            <b-form-input
                                v-model="form.fees"
                                autocomplete="off"
                                type="number"
                                step=".01"
                                min="0"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <b-col sm>
                    <validation-provider
                        :name="$t('Attendee')"
                        vid="attendee"
                        :rules="{  }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Attendee')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.attendee"
                                autocomplete="off"
                                type="text"
                                list="attendee-list"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                        <b-form-datalist id="attendee-list" :options="attendees" />
                    </validation-provider>
                </b-col>

            </b-form-row>

            <p class="d-flex justify-content-between align-items-start">
                <span>
                    <!-- Submit -->
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="disabled || !loaded"
                    >
                        <font-awesome-icon icon="check" />
                        {{ transaction ? $t("Update") : $t("Add") }}
                    </b-button>

                    <!-- Cancel -->
                    <b-button
                        variant="link"
                        :disabled="disabled || !loaded"
                        @click="$emit('cancel')"
                    >
                        {{ $t("Cancel") }}
                    </b-button>
                </span>

                <!-- Delete -->
                <b-button
                    v-if="transaction && transaction.can_delete"
                    variant="link"
                    :disabled="disabled || !loaded"
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
export default {
    props: {
        transaction: {
            type: Object,
            required: false
        },
        disabled: Boolean
    },
    data() {
        return {
            form: this.transaction
                ? {
                      receipt_no: this.transaction.receipt_no,
                      date: this.transaction.date,
                      amount: this.transaction.amount,
                      fees: this.transaction.fees,
                      attendee: this.transaction.attendee,
                  }
                : {
                      receipt_no: null,
                      date: null,
                      amount: null,
                      fees: null,
                      attendee: null,
                  },
            loaded: false,
            attendees: []
        };
    },
    async created() {
        this.loaded = true;
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onSubmit() {
            this.$emit("submit", this.form);
        },
        onDelete() {
            if (
                confirm(
                    this.$t("Do you really want to delete this transaction?")
                )
            ) {
                this.$emit("delete");
            }
        }
    }
};
</script>
