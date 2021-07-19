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
        </base-table>
    </div>
</template>

<script>
import moment from "moment";
import numeral from "numeral";
import walletsApi from "@/api/accounting/wallets";
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
            // fields: [
            //     {
            //         key: "name",
            //         label: this.$t("Name")
            //     },
            //     {
            //         key: "amount",
            //         label: this.$t("Amount"),
            //         class: "fit text-right",
            //         formatter: value => numeral(value).format("0,0.00")
            //     },
            //     {
            //         key: "num_transactions",
            //         label: this.$t("Transactions"),
            //         class: "fit text-right"
            //     },
            //     {
            //         key: "is_default",
            //         label: this.$t("Default"),
            //         class: "fit text-center"
            //     },
            //     {
            //         key: "is_restricted",
            //         label: this.$t("Restricted"),
            //         class: "fit text-center"
            //     },
            //     {
            //         key: "created_at",
            //         label: this.$t("Created"),
            //         class: "fit",
            //         formatter: this.dateFormat
            //     },
            //     {
            //         key: "latest_activity",
            //         label: this.$t("Latest activity"),
            //         class: "fit text-right",
            //         formatter: this.dateTimeFormat
            //     }
            // ]
        };
    },
    methods: {
        async fetchData(ctx) {
            return transactionsApi.list(this.wallet, ctx);
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
