<template>
    <b-container class="px-0">
        <base-table
            ref="table"
            id="wallets-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="name"
            :empty-text="$t('No wallets found.')"
            :items-per-page="25"
            no-filter
        >
            <template v-slot:cell(name)="data">
                <router-link
                    v-if="data.item.can_update"
                    :to="{
                        name: 'accounting.wallets.edit',
                        params: { id: data.item.id }
                    }"
                >
                    {{ data.value }}
                </router-link>
                <template v-else>
                    {{ data.value }}
                </template>
            </template>
            <template v-slot:cell(num_transactions)="data">
                <router-link
                    :to="{
                        name: 'accounting.transactions.index',
                        params: { wallet: data.item.id }
                    }"
                >
                    {{ data.value }}
                </router-link>
            </template>
            <template v-slot:cell(is_restricted)="data">
                <font-awesome-icon :icon="data.value ? 'check' : 'times'" />
            </template>
        </base-table>
    </b-container>
</template>

<script>
import walletsApi from "@/api/accounting/wallets";
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
                    label: this.$t("Name")
                },
                {
                    key: "num_transactions",
                    label: this.$t("Transactions"),
                    class: "fit text-right"
                },
                {
                    key: "amount_formatted",
                    label: this.$t("Amount"),
                    class: "fit text-right",
                    tdClass: (value, key, item) =>
                        item.amount < 0 ? "text-danger" : null
                },
                {
                    key: "is_restricted",
                    label: this.$t("Restricted"),
                    class: "fit text-center"
                },
                {
                    key: "latest_activity",
                    label: this.$t("Latest activity"),
                    class: "fit text-right",
                    formatter: this.dateTimeFormat
                }
            ]
        };
    },
    methods: {
        async fetchData(ctx) {
            return walletsApi.list(ctx);
        }
    }
};
</script>
