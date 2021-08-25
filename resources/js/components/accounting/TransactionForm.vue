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
                <!-- Type -->
                <b-col sm="auto" class="pb-3">
                    <validation-provider
                        :name="$t('Type')"
                        vid="type"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Type')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-radio-group
                                v-model="form.type"
                                :options="typeOptions"
                                stacked
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

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
                <b-col sm>
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
                                required
                                :options="categories"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
                <b-col sm v-if="useSecondaryCategories">
                    <validation-provider
                        :name="$t('Secondary Category')"
                        vid="secondary_category"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Secondary Category')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.secondary_category"
                                :options="secondaryCategoryOptions"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
                <b-col sm>
                    <validation-provider
                        :name="$t('Project')"
                        vid="project_id"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Project')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.project_id"
                                :options="projects"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <b-form-row>
                <b-col sm v-if="useLocations">
                    <validation-provider
                        :name="$t('Location')"
                        vid="location"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Location')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.location"
                                :options="locationOptions"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <b-col sm v-if="useCostCenters">
                    <validation-provider
                        :name="$t('Cost Center')"
                        vid="cost_center"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Cost Center')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.cost_center"
                                :options="costCenterOptions"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <b-form-row>
                <b-col sm>
                    <validation-provider
                        :name="$t('Description')"
                        vid="description"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Description')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.description"
                                autocomplete="off"
                                required
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
                <b-col sm>
                    <validation-provider
                        :name="$t('Supplier')"
                        vid="supplier_id"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Supplier')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.supplier_id"
                                :options="supplierOptions"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <b-form-row>
                <b-col sm>
                    <validation-provider
                        :name="$t('Remarks')"
                        vid="remarks"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Remarks')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.remarks"
                                autocomplete="off"
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
import moment from "moment";
import transactionsApi from "@/api/accounting/transactions";
import categoriesApi from "@/api/accounting/categories";
import projectsApi from "@/api/accounting/projects";
import suppliersApi from "@/api/accounting/suppliers";
export default {
    props: {
        transaction: {
            type: Object,
            required: false
        },
        defaultReceiptNumber: {
            required: false,
            type: Number
        },
        disabled: Boolean,
        useSecondaryCategories: Boolean,
        useLocations: Boolean,
        useCostCenters: Boolean
    },
    data() {
        return {
            form: this.transaction
                ? {
                      receipt_no: this.transaction.receipt_no,
                      date: this.transaction.date,
                      type: this.transaction.type,
                      amount: this.transaction.amount,
                      fees: this.transaction.fees,
                      attendee: this.transaction.attendee,
                      category_id: this.transaction.category_id,
                      secondary_category: this.transaction.secondary_category,
                      project_id: this.transaction.project_id,
                      location: this.transaction.location,
                      cost_center: this.transaction.cost_center,
                      description: this.transaction.description,
                      supplier_id: this.transaction.supplier_id,
                      remarks: this.transaction.remarks
                  }
                : {
                      receipt_no: this.defaultReceiptNumber ? this.defaultReceiptNumber : null,
                      date: moment().format(moment.HTML5_FMT.DATE),
                      type: null,
                      amount: null,
                      fees: null,
                      attendee: null,
                      category_id: null,
                      secondary_category: null,
                      project_id: null,
                      location: null,
                      cost_center: null,
                      description: null,
                      supplier_id: null,
                      remarks: null
                  },
            loaded: false,
            typeOptions: [
                {
                    value: "income",
                    text: this.$t("Income")
                },
                {
                    value: "spending",
                    text: this.$t("Spending")
                }
            ],
            attendees: [],
            categories: [],
            secondaryCategories: [],
            projects: [],
            locations: [],
            costCenters: [],
            suppliers: []
        };
    },
    computed: {
        secondaryCategoryOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("Secondary Category")} -`
                }
            ];
            arr.push(
                ...this.secondaryCategories.map(e => ({
                    value: e,
                    text: e
                }))
            );
            return arr;
        },
        locationOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("Location")} -`
                }
            ];
            arr.push(
                ...this.locations.map(e => ({
                    value: e,
                    text: e
                }))
            );
            return arr;
        },
        costCenterOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("Cost Center")} -`
                }
            ];
            arr.push(
                ...this.costCenters.map(e => ({
                    value: e,
                    text: e
                }))
            );
            return arr;
        },
        supplierOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("Supplier")} -`
                }
            ];
            arr.push(
                ...this.suppliers.map(e => ({
                    value: e.id,
                    text: e.name + (e.category ? ` (${e.category})` : "")
                }))
            );
            return arr;
        }
    },
    async created() {
        await this.fetchCategoryTree();
        await this.fetchProjectTree();
        const taxonomies = await transactionsApi.taxonomies();
        if (this.useSecondaryCategories) {
            this.secondaryCategories = taxonomies.secondary_categories;
        }
        if (this.useLocations) {
            this.locations = taxonomies.locations;
        }
        if (this.useCostCenters) {
            this.costCenters = taxonomies.cost_centers;
        }
        this.attendees = taxonomies.attendees;
        this.suppliers = await suppliersApi.names();
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
        async fetchCategoryTree() {
            let data = await categoriesApi.tree({ exclude: this.category?.id });
            this.categories = !this.transaction
                ? [
                      {
                          text: "- " + this.$t("Category") + " -",
                          value: null
                      }
                  ]
                : [];
            for (let elem of data) {
                this.fillTree(this.categories, elem);
            }
        },
        async fetchProjectTree() {
            let data = await projectsApi.tree({ exclude: this.category?.id });
            this.projects = [
                {
                    text: "- " + this.$t("Project") + " -",
                    value: null
                }
            ];
            for (let elem of data) {
                this.fillTree(this.projects, elem);
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