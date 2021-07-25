<template>
    <div>
        <base-table
            ref="table"
            id="transactions-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="created_at"
            :default-sort-desc="true"
            :empty-text="$t('No transactions found.')"
            :items-per-page="25"
        >
            <template v-slot:filter-prepend>
                <b-input-group :prepend="$t('Wallet')" :append="walletAmount">
                    <b-select
                        v-model="currentWallet"
                        :options="walletOptions"
                        :disabled="isBusy"
                    />
                </b-input-group>
            </template>

            <template v-slot:filter-append>
                <b-button variant="secondary" v-b-modal.filter-modal>
                    <font-awesome-icon icon="search" />
                    {{ $t("Advanced filter") }}
                </b-button>
            </template>

            <template v-slot:cell(receipt_pictures)="data">
                <ReceiptPictureUpload
                    :transaction="data.item.id"
                    :value="data.value"
                />
            </template>

            <template v-slot:cell(receipt_no)="data">
                <b-link
                    :to="{
                        name: 'accounting.transactions.show',
                        params: { id: data.item.id }
                    }"
                >
                    {{ data.value }}
                </b-link>
            </template>

            <template v-slot:cell(description)="data">
                {{ data.value }}
                <small v-if="data.item.remarks" class="d-block text-muted">{{
                    data.item.remarks
                }}</small>
            </template>

            <template v-slot:cell(supplier)="data">
                <b-link
                    v-if="data.item.supplier"
                    :to="{
                        name: 'accounting.suppliers.show',
                        params: { id: data.item.supplier.slug }
                    }"
                    :title="data.item.supplier.category"
                >
                    {{ data.item.supplier.name }}
                </b-link>
            </template>
        </base-table>

        <b-modal id="filter-modal" :title="$t('Advanced filter')">
            <b-form-row>
                <b-col sm="3" class="mb-3">
                    <b-form-group :label="$t('Type')">
                        <b-form-radio-group
                            v-model="filter.type"
                            :options="typeOptions"
                            stacked
                        />
                    </b-form-group>
                </b-col>
                <b-col sm="3" class="mb-3">
                    <b-form-group :label="$t('Controlled')">
                        <b-form-radio-group
                            v-model="filter.controlled"
                            :options="controlledOptions"
                            stacked
                        />
                    </b-form-group>
                </b-col>
                <b-col sm="3" class="mb-3">
                    <b-form-group :label="$t('Receipt')">
                        <b-form-input
                            type="number"
                            v-model="filter.receipt_no"
                            min="1"
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
                    <!-- TODO datalist? -->
                    <b-form-group :label="$t('Attendee')">
                        <b-form-input v-model="filter.attendee" />
                    </b-form-group>
                </b-col>
                <b-col sm>
                    <b-form-group :label="$t('Description')">
                        <b-form-input v-model="filter.description" />
                    </b-form-group>
                </b-col>
            </b-form-row>
            <b-form-row>
                <b-col sm>
                    <!-- TODO datalist? -->
                    <b-form-group :label="$t('Supplier')">
                        <b-form-input v-model="filter.supplier" />
                    </b-form-group>
                </b-col>
                <b-col sm>
                    <b-form-checkbox v-model="filter.today">
                        {{ $t("Registered today") }}
                    </b-form-checkbox>
                    <b-form-checkbox v-model="filter.no_receipt">
                        {{ $t("No receipt") }}
                    </b-form-checkbox>
                </b-col>
            </b-form-row>
        </b-modal>
    </div>
</template>

<script>
import moment from "moment";
import numeral from "numeral";
import walletsApi from "@/api/accounting/wallets";
import transactionsApi from "@/api/accounting/transactions";
import categoriesApi from "@/api/accounting/categories";
import projectsApi from "@/api/accounting/projects";
import BaseTable from "@/components/table/BaseTable";
import ReceiptPictureUpload from "@/components/accounting/ReceiptPictureUpload";
export default {
    components: {
        BaseTable,
        ReceiptPictureUpload
    },
    props: {
        wallet: {
            required: true
        },
        useSecondaryCategories: Boolean,
        useLocations: Boolean,
        useCostCenters: Boolean,
        showIntermediateBalances: Boolean
    },
    data() {
        return {
            isBusy: false,
            wallets: [],
            categories: [],
            secondaryCategories: [],
            projects: [],
            locations: [],
            costCenters: [],
            filter: {
                type: null,
                controlled: null,
                receipt_no: null,
                date_start: null,
                date_end: null,
                category_id: null,
                secondary_category: null,
                project_id: null,
                location: null,
                cost_center: null,
                attendee: null,
                description: null,
                supplier: null,
                today: false,
                no_receipt: false
            },
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
            ],
            fields: [
                {
                    key: "receipt_pictures",
                    label: ""
                },
                {
                    key: "receipt_no",
                    label: this.$t("Receipt"),
                    sortable: true
                },
                {
                    key: "date",
                    label: this.$t("Date"),
                    sortable: true,
                    sortDirection: "desc",
                    formatter: this.dateFormat
                },
                {
                    key: "amount",
                    label: this.$t("Amount"),
                    class: "fit text-right",
                    tdClass: (value, key, item) =>
                        item.type == "income" ? "text-success" : "text-danger",
                    formatter: (value, key, item) =>
                        (item.type == "spending" ? "-" : "") +
                        this.numberFormat(value)
                },
                this.showIntermediateBalances
                    ? {
                          key: "intermediate_balance",
                          label: this.$t("Intermediate balance"),
                          class: "fit text-right",
                          tdClass: (value, key, item) =>
                              value > 0 ? "text-success" : "text-danger"
                      }
                    : null,
                {
                    key: "category_full_name",
                    label: this.$t("Category")
                },
                this.useSecondaryCategories
                    ? {
                          key: "secondary_category",
                          label: this.$t("Secondary Category")
                      }
                    : null,
                {
                    key: "project_full_name",
                    label: this.$t("Project")
                },
                this.useLocations
                    ? {
                          key: "location",
                          label: this.$t("Location")
                      }
                    : null,
                this.useCostCenters
                    ? {
                          key: "cost_center",
                          label: this.$t("Cost Center")
                      }
                    : null,
                {
                    key: "description",
                    label: this.$t("Description")
                },
                {
                    key: "supplier",
                    label: this.$t("Supplier")
                },
                {
                    key: "attendee",
                    label: this.$t("Attendee")
                },
                {
                    key: "created_at",
                    label: this.$t("Registered"),
                    formatter: this.dateTimeFormat,
                    sortable: true,
                    sortDirection: "desc"
                }
            ]
        };
    },
    computed: {
        currentWallet: {
            get() {
                return this.wallet;
            },
            set(value) {
                this.$router.push({
                    name: "accounting.transactions.index",
                    params: { wallet: value }
                });
            }
        },
        walletAmount() {
            if (this.wallets.length == 0) {
                return null;
            }
            const wallet = this.wallets.filter(w => w.id == this.wallet)[0];
            return this.numberFormat(wallet.amount);
        },
        walletOptions() {
            return this.wallets.map(e => ({
                value: e.id,
                text: e.name
            }));
        },
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
        }
    },
    watch: {
        wallet() {
            this.$refs.table.refresh();
        }
    },
    async created() {
        this.wallets = (await walletsApi.list()).data;
        this.categories = await categoriesApi.tree();
        if (this.useSecondaryCategories) {
            this.secondaryCategories = await transactionsApi.secondaryCategories();
        }
        this.projects = await projectsApi.tree();
        if (this.useLocations) {
            this.locations = await transactionsApi.locations();
        }
        if (this.useCostCenters) {
            this.costCenters = await transactionsApi.costCenters();
        }
    },
    methods: {
        fetchData(ctx) {
            return transactionsApi.list(this.wallet, ctx);
        },
        dateFormat(value) {
            return value ? moment(value).format("LL") : null;
        },
        dateTimeFormat(value) {
            return value ? moment(value).format("LLL") : null;
        },
        numberFormat(val) {
            return numeral(val).format("0,0.00");
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
