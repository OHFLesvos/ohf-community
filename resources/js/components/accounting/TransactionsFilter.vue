<template>
    <div>
        <b-button :variant="buttonVariant" @click="modalShow = !modalShow">
            <font-awesome-icon icon="search" />
            {{ $t("Advanced filter") }}
        </b-button>
        <b-modal
            v-model="modalShow"
            :title="$t('Advanced filter')"
            footer-class="d-flex justify-content-between"
            @show="initFilter"
            @ok="applyFilter"
        >
            <b-form-row>
                <b-col sm class="mb-3">
                    <b-form-group :label="$t('Type')">
                        <b-form-radio-group
                            v-model="filter.type"
                            :options="typeOptions"
                            stacked
                        />
                    </b-form-group>
                </b-col>
                <b-col sm class="mb-3">
                    <b-form-group :label="$t('Controlled')">
                        <b-form-radio-group
                            v-model="filter.controlled"
                            :options="controlledOptions"
                            stacked
                        />
                    </b-form-group>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm class="mb-3">
                    <b-form-group :label="$t('Receipt')">
                        <b-form-input
                            type="number"
                            v-model="filter.receipt_no"
                            min="1"
                        />
                    </b-form-group>
                </b-col>
                <b-col sm class="mb-3">
                    <b-form-group :label="$t('Amount')">
                        <b-form-input
                            type="number"
                            step="any"
                            v-model="filter.amount"
                        />
                    </b-form-group>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <b-form-group :label="$t('From')">
                        <b-form-input type="date" v-model="filter.date_start" />
                    </b-form-group>
                </b-col>
                <b-col sm>
                    <b-form-group :label="$t('To')">
                        <b-form-input type="date" v-model="filter.date_end" />
                    </b-form-group>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <b-form-group :label="$t('Category')">
                        <b-select
                            v-model="filter.category_id"
                            :options="categoryOptions"
                        />
                    </b-form-group>
                </b-col>
                <b-col sm v-if="useSecondaryCategories">
                    <b-form-group :label="$t('Secondary Category')">
                        <b-select
                            v-model="filter.secondary_category"
                            :options="secondaryCategoryOptions"
                        />
                    </b-form-group>
                </b-col>
                <b-col sm>
                    <b-form-group :label="$t('Project')">
                        <b-select
                            v-model="filter.project_id"
                            :options="projectOptions"
                        />
                    </b-form-group>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm v-if="useLocations">
                    <b-form-group :label="$t('Location')">
                        <b-select
                            v-model="filter.location"
                            :options="locationOptions"
                        />
                    </b-form-group>
                </b-col>
                <b-col sm v-if="useCostCenters">
                    <b-form-group :label="$t('Cost Center')">
                        <b-select
                            v-model="filter.cost_center"
                            :options="costCenterOptions"
                        />
                    </b-form-group>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <b-form-group :label="$t('Attendee')">
                        <b-form-input
                            v-model="filter.attendee"
                            trim
                            list="attendee-list"
                        />
                    </b-form-group>
                    <b-form-datalist id="attendee-list" :options="attendees" />
                </b-col>
                <b-col sm>
                    <!-- TODO datalist? -->
                    <b-form-group :label="$t('Supplier')">
                        <b-form-input v-model="filter.supplier" trim />
                    </b-form-group>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <b-form-group :label="$t('Description')">
                        <b-form-input v-model="filter.description" trim />
                    </b-form-group>
                </b-col>
                <b-col sm>
                    <b-form-group :label="$t('Remarks')">
                        <b-form-input v-model="filter.remarks" trim />
                    </b-form-group>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <b-form-checkbox v-model="filter.today">
                        {{ $t("Registered today") }}
                    </b-form-checkbox>
                    <b-form-checkbox v-model="filter.no_receipt">
                        {{ $t("No receipt") }}
                    </b-form-checkbox>
                </b-col>
            </b-form-row>

            <template #modal-footer="{ ok, cancel }">
                <b-button variant="outline-secondary" @click="resetFilter()">
                    {{ $t("Reset filter") }}
                </b-button>
                <span>
                    <b-button variant="secondary" @click="cancel()">
                        {{ $t("Cancel") }}
                    </b-button>
                    <b-button variant="primary" @click="ok()">
                        {{ $t("Apply filter") }}
                    </b-button>
                </span>
            </template>
        </b-modal>
    </div>
</template>

<script>
import transactionsApi from "@/api/accounting/transactions";
import categoriesApi from "@/api/accounting/categories";
import projectsApi from "@/api/accounting/projects";
export default {
    props: {
        value: {
            required: true,
            type: Object
        },
        useSecondaryCategories: Boolean,
        useLocations: Boolean,
        useCostCenters: Boolean
    },
    data() {
        return {
            modalShow: false,
            categories: [],
            secondaryCategories: [],
            projects: [],
            locations: [],
            costCenters: [],
            attendees: [],
            filter: this.createEmptyFilter(),
            typeOptions: [
                {
                    value: "income",
                    text: this.$t("Income")
                },
                {
                    value: "spending",
                    text: this.$t("Spending")
                },
                {
                    value: null,
                    text: this.$t("Any")
                }
            ],
            controlledOptions: [
                {
                    value: "yes",
                    text: this.$t("Yes")
                },
                {
                    value: "no",
                    text: this.$t("No")
                },
                {
                    value: null,
                    text: this.$t("Any")
                }
            ]
        };
    },
    computed: {
        categoryOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("Category")} -`
                }
            ];
            for (let elem of this.categories) {
                this.fillTree(arr, elem);
            }
            return arr;
        },
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
        projectOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("Project")} -`
                }
            ];
            for (let elem of this.projects) {
                this.fillTree(arr, elem);
            }
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
        buttonVariant() {
            if (Object.keys(this.cleanObject(this.value)).length > 0) {
                return "warning";
            }
            return "secondary";
        }
    },
    async created() {
        this.categories = await categoriesApi.tree();
        this.projects = await projectsApi.tree();
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
    },
    methods: {
        cleanObject(value) {
            return Object.fromEntries(
                Object.entries(value).filter(
                    ([_, v]) => !(v == null || v === false || v == "")
                )
            );
        },
        createEmptyFilter() {
            return {
                type: null,
                controlled: null,
                receipt_no: null,
                amount: null,
                date_start: null,
                date_end: null,
                category_id: null,
                secondary_category: null,
                project_id: null,
                location: null,
                cost_center: null,
                attendee: null,
                description: null,
                remarks: null,
                supplier: null,
                today: null,
                no_receipt: null
            };
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
        },
        initFilter() {
            var propNames = Object.getOwnPropertyNames(this.filter);
            for (var i = 0; i < propNames.length; i++) {
                var propName = propNames[i];
                this.filter[propName] = this.value[propName] ?? null;
            }
        },
        applyFilter() {
            this.$emit("input", this.cleanObject(this.filter));
        },
        resetFilter() {
            this.filter = this.createEmptyFilter();
            this.applyFilter();
            this.modalShow = false;
        }
    }
};
</script>
