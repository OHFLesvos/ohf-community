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
                        v-model="wallet"
                        :options="walletOptions"
                        :disabled="isBusy"
                    />
                </b-input-group>
            </template>

            <template v-slot:filter-append="data">
                <b-form-row>
                    <b-col>
                        <TransactionsFilter
                            v-model="advancedFilter"
                            :use-secondary-categories="useSecondaryCategories"
                            :use-locations="useLocations"
                            :use-cost-centers="useCostCenters"
                        />
                    </b-col>
                    <b-col v-if="can('view-transactions')" cols="auto">
                        <TransactionExportDialog
                            :wallet="wallet"
                            :filter="data.filter"
                            :advancedFilter="advancedFilter"
                        />
                    </b-col>
                </b-form-row>
            </template>

            <template v-slot:cell(receipt_pictures)="data">
                <ReceiptPictureUpload
                    :transaction="data.item.id"
                    :value="data.value"
                />
            </template>

            <template v-slot:cell(receipt_no)="data">
                <router-link
                    :to="{
                        name: 'accounting.transactions.show',
                        params: { id: data.item.id }
                    }"
                >
                    {{ data.value }}
                </router-link>
            </template>

            <template v-slot:cell(description)="data">
                {{ data.value }}
                <small v-if="data.item.remarks" class="d-block text-muted">{{
                    data.item.remarks
                }}</small>
            </template>

            <template v-slot:cell(budget_name)="data">
                <router-link
                    v-if="can('view-budgets') && data.item.budget_id"
                    :to="{
                        name: 'accounting.budgets.show',
                        params: { id: data.item.budget_id }
                    }"
                    >{{ data.value }}</router-link
                >
                <template v-else>
                    {{ data.value }}
                </template>
            </template>

            <template v-slot:cell(supplier)="data">
                <router-link
                    v-if="data.item.supplier"
                    :to="{
                        name: 'accounting.suppliers.show',
                        params: { id: data.item.supplier.slug }
                    }"
                    :title="data.item.supplier.category"
                >
                    {{ data.item.supplier.name }}
                </router-link>
            </template>
        </base-table>
    </div>
</template>

<script>
const ADVANCED_FILTER_SESSION_KEY = "accounting.transactions.advancedFilter";
import qs from "qs";
import walletsApi from "@/api/accounting/wallets";
import transactionsApi from "@/api/accounting/transactions";
import BaseTable from "@/components/table/BaseTable";
import ReceiptPictureUpload from "@/components/accounting/ReceiptPictureUpload";
import TransactionsFilter from "@/components/accounting/TransactionsFilter";
import TransactionExportDialog from "@/components/accounting/TransactionExportDialog";
export default {
    components: {
        BaseTable,
        ReceiptPictureUpload,
        TransactionsFilter,
        TransactionExportDialog
    },
    props: {
        useSecondaryCategories: Boolean,
        useLocations: Boolean,
        useCostCenters: Boolean,
        showIntermediateBalances: Boolean
    },
    data() {
        let advancedFilter = {};
        const queryParams = qs.parse(this.$route.query);
        if (queryParams.filter && Object.keys(queryParams.filter).length > 0) {
            advancedFilter = queryParams.filter;
            sessionStorage.removeItem(ADVANCED_FILTER_SESSION_KEY);
        } else {
            const persitedAdvancedFilter = sessionStorage.getItem(
                ADVANCED_FILTER_SESSION_KEY
            );
            if (persitedAdvancedFilter) {
                advancedFilter = JSON.parse(persitedAdvancedFilter);
            }
        }
        return {
            isBusy: false,
            wallet: this.$route.query.wallet ?? null,
            wallets: [],
            advancedFilter: advancedFilter,
            fields: [
                {
                    key: "wallet_name",
                    label: this.$t("Wallet")
                },
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
                    key: "amount_formatted",
                    label: this.$t("Amount"),
                    class: "fit text-right",
                    tdClass: (value, key, item) =>
                        item.type == "income" ? "text-success" : "text-danger",
                    formatter: (value, key, item) =>
                        (item.type == "spending" ? "-" : "") + value
                },
                this.showIntermediateBalances
                    ? {
                          key: "intermediate_balance",
                          label: this.$t("Intermediate balance"),
                          class: "fit text-right",
                          tdClass: value =>
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
                    key: "budget_name",
                    label: this.$t("Budget")
                },
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
        walletAmount() {
            if (this.wallets.length == 0) {
                return null;
            }
            const wallet = this.wallets.filter(w => w.id == this.wallet)[0];
            if (wallet) {
                return wallet.amount_formatted;
            }
            return null;
        },
        walletOptions() {
            return [
                {
                    value: null,
                    text: `- ${this.$t("All wallets")} -`
                },
                ...this.wallets.map(e => ({
                    value: e.id,
                    text: e.name
                }))
            ];
        }
    },
    watch: {
        wallet() {
            this.$refs.table.refresh();
            this.emitChange();
        },
        advancedFilter(value) {
            sessionStorage.setItem(
                ADVANCED_FILTER_SESSION_KEY,
                JSON.stringify(value)
            );
            this.$refs.table.refresh();
        }
    },
    async created() {
        this.wallets = (await walletsApi.list()).data;
    },
    methods: {
        fetchData(ctx) {
            if (Object.keys(this.advancedFilter).length > 0) {
                Object.entries(this.advancedFilter).forEach(function([
                    key,
                    value
                ]) {
                    ctx[`advanced_filter[${key}]`] = value;
                });
            }
            ctx["wallet"] = this.wallet;
            return transactionsApi.list(ctx);
        },
        emitChange() {
            this.$emit("change", {
                wallet: this.wallet || undefined
            });
        }
    }
};
</script>
