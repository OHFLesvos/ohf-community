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
                      date: this.transaction.date
                  }
                : {
                      receipt_no: null,
                      date: null
                  },
            loaded: false
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
