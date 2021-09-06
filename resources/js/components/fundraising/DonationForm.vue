<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit, invalid }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-form-row>
                <!-- Date -->
                <b-col md>
                    <validation-provider
                        :name="$t('Date')"
                        vid="date"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Date')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-datepicker
                                v-model="form.date"
                                :max="maxDate"
                                required
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Currency -->
                <b-col md="auto">
                    <validation-provider
                        :name="$t('Currency')"
                        vid="currency"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Currency')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.currency"
                                :options="currencyOptions"
                                required
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Amount -->
                <b-col md>
                    <validation-provider
                        :name="$t('Amount')"
                        vid="amount"
                        :rules="{ required: true, decimal: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Amount')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                ref="amount"
                                v-model="form.amount"
                                type="number"
                                min="1"
                                required
                                step="any"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Exchange rate -->
                <b-col v-if="form.currency != baseCurrency" md>
                    <validation-provider
                        :name="$t('Exchange rate')"
                        vid="exchange_rate"
                        :rules="{ decimal: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Exchange rate')"
                            :description="
                                $t('Leave empty for automatic calculation')
                            "
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.exchange_rate"
                                type="number"
                                min="0"
                                step="any"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>
            <b-form-row>
                <!-- Channel -->
                <b-col md="4">
                    <validation-provider
                        :name="$t('Channel')"
                        vid="channel"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Channel')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.channel"
                                required
                                list="channel-list"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                        <b-form-datalist
                            id="channel-list"
                            :options="channels"
                        />
                    </validation-provider>
                </b-col>

                <!-- Purpose -->
                <b-col md>
                    <validation-provider
                        :name="$t('Purpose')"
                        vid="purpose"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Purpose')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.purpose"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Accounting category -->
                <b-col md>
                    <validation-provider
                        :name="$t('Accounting category')"
                        vid="accounting_category_id"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Accounting category')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.accounting_category_id"
                                :options="categoryTree"
                                :disabled="!loaded"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Budget -->
                <b-col md>
                    <validation-provider
                        :name="$t('Budget')"
                        vid="budget_id"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Budget')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.budget_id"
                                :options="budgetOptions"
                                :disabled="!loaded"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>
            <b-form-row>
                <!-- Reference -->
                <b-col md="4">
                    <validation-provider
                        :name="$t('Reference')"
                        vid="reference"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Reference')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.reference"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- In name of -->
                <b-col md>
                    <validation-provider
                        :name="$t('In the name of')"
                        vid="in_name_of"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="`${$t('In the name of')}...`"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.in_name_of"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <p>
                <b-form-checkbox v-model="form.thanked">
                    {{ $t("Donor has been thanked") }}
                </b-form-checkbox>
            </p>

            <p class="d-flex justify-content-between align-items-start">
                <span>
                    <!-- Submit -->
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="disabled || invalid"
                    >
                        <font-awesome-icon icon="check" />
                        {{ donation ? $t("Update") : $t("Add") }}
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
                    v-if="donation && donation.can_delete"
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
import moment from "moment";
import categoriesApi from "@/api/accounting/categories";
import budgetsApi from "@/api/accounting/budgets";
export default {
    props: {
        donation: {
            type: Object,
            required: false
        },
        currencies: {
            required: true,
            type: Object
        },
        channels: {
            required: true,
            type: Array
        },
        baseCurrency: {
            required: true
        },
        disabled: Boolean
    },
    data() {
        return {
            loaded: false,
            form: this.donation
                ? {
                      date: this.donation.date,
                      currency: this.donation.currency,
                      amount: this.donation.amount,
                      exchange_rate: this.donation.exchange_rate,
                      channel: this.donation.channel,
                      purpose: this.donation.purpose,
                      reference: this.donation.reference,
                      in_name_of: this.donation.in_name_of,
                      thanked: this.donation.thanked != null,
                      accounting_category_id: this.donation
                          .accounting_category_id,
                      budget_id: this.donation.budget_id
                  }
                : {
                      date: moment().format(moment.HTML5_FMT.DATE),
                      currency: this.baseCurrency,
                      amount: null,
                      exchange_rate: null,
                      channel: null,
                      purpose: null,
                      reference: null,
                      in_name_of: null,
                      thanked: false,
                      accounting_category_id: null,
                      budget_id: null
                  },
            categoryTree: [],
            budgets: []
        };
    },
    computed: {
        maxDate() {
            return moment().format(moment.HTML5_FMT.DATE);
        },
        currencyOptions() {
            return Object.entries(this.currencies).map(function(e) {
                return {
                    value: e[0],
                    text: e[1]
                };
            });
        },
        budgetOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("No budget")} -`
                }
            ];
            arr.push(
                ...this.budgets
                    .filter(
                        budget =>
                            !budget.is_completed ||
                            this.transaction?.budget_id == budget.id
                    )
                    .map(e => ({
                        value: e.id,
                        text: e.name
                    }))
            );
            return arr;
        }
    },
    async created() {
        await this.fetchTree();
        this.budgets = await budgetsApi.names();
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
                confirm(this.$t("Do you really want to delete this donation?"))
            ) {
                this.$emit("delete");
            }
        },
        async fetchTree() {
            let data = await categoriesApi.tree();
            this.categoryTree = [
                {
                    text: " ",
                    value: null
                }
            ];
            for (let elem of data) {
                this.fillTree(this.categoryTree, elem);
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
