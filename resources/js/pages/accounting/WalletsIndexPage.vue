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
                <b-link
                    v-if="data.item.can_update"
                    :to="{
                        name: 'accounting.wallets.edit',
                        params: { id: data.item.id }
                    }"
                >
                    {{ data.value }}
                </b-link>
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
            <template v-slot:cell(is_default)="data">
                <font-awesome-icon :icon="data.value ? 'check' : 'times'" />
            </template>
            <template v-slot:cell(is_restricted)="data">
                <font-awesome-icon :icon="data.value ? 'check' : 'times'" />
            </template>
        </base-table>
    </b-container>
</template>

<script>
import moment from "moment";
import walletsApi from "@/api/accounting/wallets";
import BaseTable from "@/components/table/BaseTable";
import numberFormatMixin from '@/mixins/numberFormatMixin'
export default {
    components: {
        BaseTable
    },
        mixins: [
        numberFormatMixin
    ],
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
                    key: "num_transactions",
                    label: this.$t("Transactions"),
                    class: "fit text-right"
                },
                {
                    key: "is_default",
                    label: this.$t("Default"),
                    class: "fit text-center"
                },
                {
                    key: "is_restricted",
                    label: this.$t("Restricted"),
                    class: "fit text-center"
                },
                {
                    key: "created_at",
                    label: this.$t("Created"),
                    class: "fit",
                    formatter: this.dateFormat
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
        },
        dateFormat(value) {
            return value ? moment(value).format("LL") : null;
        },
        dateTimeFormat(value) {
            return value ? moment(value).format("LLL") : null;
        }
    }
};
</script>
