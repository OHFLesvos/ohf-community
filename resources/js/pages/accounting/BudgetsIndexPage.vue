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
            :tbody-tr-class="rowClass"
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
            <template v-slot:cell(is_completed)="data">
                <font-awesome-icon :icon="data.value ? 'check' : 'times'" />
            </template>
        </base-table>
    </b-container>
</template>

<script>
import budgetsApi from "@/api/accounting/budgets";
import BaseTable from "@/components/table/BaseTable.vue";
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
                    key: "agreed_amount_formatted",
                    label: this.$t("Agreed amount"),
                    class: "fit text-right"
                },
                {
                    key: "balance_formatted",
                    label: this.$t("Balance"),
                    class: "fit text-right",
                    tdClass: (value, key, item) =>
                        item.balance < 0 ? "text-danger" : null
                },
                {
                    key: "num_transactions",
                    label: this.$t("Transactions"),
                    class: "fit text-right"
                },
                {
                    key: "closed_at",
                    label: this.$t("Closing date"),
                    class: "fit",
                    formatter: this.dateFormat
                },
                {
                    key: "is_completed",
                    label: this.$t("Completed"),
                    class: "fit text-center"
                }
            ]
        };
    },
    methods: {
        async fetchData(ctx) {
            return budgetsApi.list(ctx);
        },
        rowClass(item, type) {
            if (!item || type !== "row") return;
            if (item.is_completed) return "table-secondary";
        }
    }
};
</script>
