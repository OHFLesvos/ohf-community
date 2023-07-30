<template>
    <div>
        <base-table
            id="donor-budgets-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="name"
            :empty-text="$t('No budgets found.')"
            :items-per-page="25"
            no-filter
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
    </div>
</template>

<script>
import BaseTable from "@/components/table/BaseTable.vue";
import donorsApi from "@/api/fundraising/donors";
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
            fields: [
                {
                    key: "name",
                    label: this.$t("Name")
                },
                {
                    key: "agreed_amount_formatted",
                    label: this.$t("Agreed amount"),
                    class: "fit text-right",
                },
                {
                    key: "balance_formatted",
                    label: this.$t("Balance"),
                    class: "fit text-right",
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
            return await donorsApi.budgets(this.id, ctx);
        },
        rowClass(item, type) {
            if (!item || type !== "row") return;
            if (item.is_completed) return "table-secondary";
        }
    }
};
</script>
