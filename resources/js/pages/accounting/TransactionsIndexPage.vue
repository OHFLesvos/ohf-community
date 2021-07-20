<template>
    <div>
        <base-table
            ref="table"
            id="transactions-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="created_at"
            :empty-text="$t('No transactions found.')"
            :items-per-page="25"
            no-filter
        >
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
// import walletsApi from "@/api/accounting/wallets";
import transactionsApi from "@/api/accounting/transactions";
import BaseTable from "@/components/table/BaseTable";
export default {
    components: {
        BaseTable
    },
    props: {
        wallet: {
            required: true
        }
    },
    data() {
        return {
            fields: [
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
                {
                    key: "category_full_name",
                    label: this.$t("Category")
                },
                {
                    key: "secondary_category",
                    label: this.$t("Secondary Category")
                },
                {
                    key: "project_full_name",
                    label: this.$t("Project")
                },
                {
                    key: "location",
                    label: this.$t("Location")
                },
                {
                    key: "cost_center",
                    label: this.$t("Cost Center")
                },
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
            ],
            useLocations: false,
            useSecondaryCategories: false,
            thiuseCostCenters: false
        };
    },
    methods: {
        async fetchData(ctx) {
            let data = await transactionsApi.list(this.wallet, ctx);
            this.useLocations = data.meta.use_locations;
            this.useSecondaryCategories = data.meta.use_secondary_categories;
            this.useCostCenters = data.meta.use_cost_centers;
            return data;
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
