<template>
    <div class="mt-3">
        <base-table
            ref="table"
            id="suppliers-transactons-table"
            :fields="transactionFields"
            :api-method="fetchTransactions"
            default-sort-by="created_at"
            default-sort-desc
            :empty-text="$t('No data registered.')"
            :items-per-page="25"
        >
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
        </base-table>
    </div>
</template>

<script>
import suppliersApi from "@/api/accounting/suppliers";
import BaseTable from "@/components/table/BaseTable";
export default {
    components: {
        BaseTable
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            transactionFields: [
                {
                    key: "receipt_no",
                    label: this.$t("Receipt No."),
                    sortable: true,
                    class: "text-right fit"
                },
                {
                    key: "date",
                    label: this.$t("Date"),
                    sortable: true,
                    formatter: this.dateFormat,
                    class: "fit"
                },
                {
                    key: "amount",
                    label: this.$t("Amount"),
                    formatter: (value, key, item) => {
                        let val = value;
                        if (item.type == "spending") {
                            val = -val;
                        }
                        return this.decimalNumberFormat(val);
                    },
                    class: "text-right fit",
                    tdClass: (value, key, item) => {
                        if (item.type == "spending") {
                            return "text-danger";
                        }
                        return "text-success";
                    }
                },
                {
                    key: "category",
                    label: this.$t("Category")
                },
                {
                    key: "description",
                    label: this.$t("Description")
                },
                {
                    key: "attendee",
                    label: this.$t("Attendee")
                },
                {
                    key: "created_at",
                    label: this.$t("Registered"),
                    sortable: true,
                    formatter: this.dateTimeFormat,
                    class: "fit"
                }
            ]
        };
    },
    methods: {
        fetchTransactions(ctx) {
            return suppliersApi.transactions(this.id, ctx);
        }
    }
};
</script>
