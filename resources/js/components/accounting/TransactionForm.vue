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

                <!-- Transaction fees -->
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
                            :description="
                                $t('Write decimal point as comma (,)')
                            "
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

                <!-- Attendee -->
                <b-col sm>
                    <validation-provider
                        :name="$t('Attendee')"
                        vid="attendee"
                        :rules="{}"
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
                        <b-form-datalist
                            id="attendee-list"
                            :options="attendees"
                        />
                    </validation-provider>
                </b-col>
            </b-form-row>

            <b-form-row>
                <b-col md>
                    <validation-provider
                        :name="$t('Category')"
                        vid="category_id"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Category')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.category_id"
                                :options="categories"
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
import categoriesApi from "@/api/accounting/categories";
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
                      category_id: this.transaction.category_id
                  }
                : {
                      receipt_no: null,
                      date: null,
                      amount: null,
                      fees: null,
                      attendee: null,
                      category_id: null
                  },
            loaded: false,
            attendees: [],
            categories: []
        };
    },
    async created() {
        await this.fetchTree();
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
        },
        async fetchTree() {
            let data = await categoriesApi.tree({ exclude: this.category?.id });
            this.categories = !this.transaction ? [
                {
                    text: '- ' + this.$t('Category') + ' -',
                    value: null
                }
            ] : [];
            for (let elem of data) {
                this.fillTree(this.categories, elem);
            }
        },
        fillTree(tree, elem, level = 0) {
            let text = "";
            if (level > 0) {
                text += "&nbsp;".repeat(level * 5);
            }
            text += elem.name;
            tree.push({
                html: text,
                value: elem.id
            });
            for (let child of elem.children) {
                this.fillTree(tree, child, level + 1);
            }
        }
    }
};
</script>
