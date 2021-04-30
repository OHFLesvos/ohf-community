<template>
    <base-table
        ref="table"
        id="categories-table"
        :fields="fields"
        :api-method="fetchData"
        default-sort-by="name"
        :empty-text="$t('No data registered.')"
        :items-per-page="25"
    >
        <template v-slot:cell(name)="data">
            <b-link
                :to="{
                    name: 'accounting.categories.edit',
                    params: { id: data.item.id }
                }"
            >
                {{ data.value }}
            </b-link>
        </template>
    </base-table>
</template>

<script>
import categoriesApi from "@/api/accounting/categories";
import BaseTable from "@/components/table/BaseTable";
export default {
    components: {
        BaseTable
    },
    data() {
        return {
            fields: [
                {
                    key: "name",
                    label: this.$t("Name"),
                    sortable: true,
                    tdClass: "align-middle fit"
                },
                {
                    key: "description",
                    label: this.$t("Description"),
                    sortable: true,
                    tdClass: "align-middle"
                },
                {
                    key: "num_transactions",
                    label: this.$t("Transactions"),
                    class: "text-right"
                }
            ]
        };
    },
    methods: {
        async fetchData(ctx) {
            return categoriesApi.list(ctx);
        }
    }
};
</script>
