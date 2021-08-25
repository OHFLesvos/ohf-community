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
                <b-form-row>
                    <b-col>
                        <TransactionsFilter
                            v-model="advancedFilter"
                            :use-secondary-categories="useSecondaryCategories"
                            :use-locations="useLocations"
                            :use-cost-centers="useCostCenters"
                        />
                    </b-col>
                    <b-col cols="auto">
                        <!-- TODO Auth::user()->can('viewAny', Transaction::class) -->
                        <TransactionExportDialog :wallet="wallet" :filter="advancedFilter" />
                    </b-col>
                    <b-col cols="auto">
                        <!-- TODO Auth::user()->can('create', Transaction::class) -->
                        <router-link
                            class="btn btn-primary"
                            :to="{
                                name: 'accounting.transactions.create',
                                params: { wallet: wallet }
                            }"
                        >
                            <font-awesome-icon icon="plus-circle" />
                            {{ $t("Add") }}
                        </router-link>
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
    </div>
</template>

<script>
import moment from "moment";
import numeral from "numeral";
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
        TransactionExportDialog,
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
        const persitedAdvancedFilter = sessionStorage.getItem(
            "accounting.transactions.advancedFilter"
        );
        return {
            isBusy: false,
            wallets: [],
            advancedFilter: persitedAdvancedFilter
                ? JSON.parse(persitedAdvancedFilter)
                : {},
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
        }
    },
    watch: {
        wallet() {
            this.$refs.table.refresh();
        },
        advancedFilter(value) {
            sessionStorage.setItem(
                "accounting.transactions.advancedFilter",
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
        }
    }
};
</script>
