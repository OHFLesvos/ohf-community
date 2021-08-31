<template>
    <b-container class="px-0">
        <base-table
            ref="table"
            id="budgets-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="name"
            :empty-text="$t('No budgets found.')"
            :items-per-page="25"
            no-filter
        >
            <template v-slot:cell(name)="data">
                <router-link
                    :to="{
                        name: 'accounting.budgets.show',
                        params: { id: data.item.id }
                    }"
                >
                    {{ data.value }}
                </router-link>
            </template>
        </base-table>
    </b-container>
</template>

<script>
import budgetsApi from "@/api/accounting/budgets";
import BaseTable from "@/components/table/BaseTable";
export default {
    title() {
        return this.$t("Budgets");
    },
    components: {
        BaseTable
    },
    data() {
        return {
            fields: [
                {
                    key: "name",
                    label: this.$t("Name")
                },
                {
                    key: "amount",
                    label: this.$t("Amount"),
                    class: "fit text-right",
                    formatter: this.decimalNumberFormat
                },
                {
                    key: "balance",
                    label: this.$t("Balance"),
                    class: "fit text-right",
                    formatter: this.decimalNumberFormat
                },
                {
                    key: "num_transactions",
                    label: this.$t("Transactions"),
                    class: "fit text-right"
                },
                {
                    key: "created_at",
                    label: this.$t("Created"),
                    class: "fit",
                    formatter: this.dateFormat
                },
                {
                    key: "closed_at",
                    label: this.$t("Completed"),
                    class: "fit",
                    formatter: this.dateFormat
                }
            ]
        };
    },
    methods: {
        async fetchData(ctx) {
            return budgetsApi.list(ctx);
        }
    }
};
</script>
