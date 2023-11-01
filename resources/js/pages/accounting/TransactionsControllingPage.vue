<template>
    <b-container fluid>
        <b-form-row class="mb-3">
            <b-col cols="auto">
                <b-input-group :prepend="$t('Wallet')">
                    <b-select
                        v-model="wallet"
                        :options="walletOptions"
                        :disabled="isBusy"
                    />
                </b-input-group>
            </b-col>
            <b-col cols="auto">
                <b-input-group :prepend="$t('From')">
                    <b-form-datepicker
                        v-model="dateFrom"
                        reset-button
                        today-button
                        :max="dateTo"
                    />
                </b-input-group>
            </b-col>
            <b-col cols="auto">
                <b-input-group :prepend="$t('To')">
                    <b-form-datepicker
                        v-model="dateTo"
                        reset-button
                        today-button
                        :min="dateFrom"
                    />
                </b-input-group>
            </b-col>
            <b-col cols="auto">
                <b-button @click="refresh" :aria-label="$t('Refresh')">
                    <font-awesome-icon icon="sync-alt" />
                </b-button>
            </b-col>
        </b-form-row>
        <base-table
            ref="table"
            id="transactions-controllable-table"
            :api-method="fetchData"
            :fields="fields"
            :empty-text="$t('No transactions found.')"
            :items-per-page="10"
            default-sort-by="date"
            no-filter
        >
            <template v-slot:cell(details)="data">
                <TransactionControllingDetails :transaction="data.item" />
            </template>
        </base-table>
    </b-container>
</template>

<script>
const sessionStoragePrefix = "accounting.transactions.controlling";
import walletsApi from "@/api/accounting/wallets";
import transactionsApi from "@/api/accounting/transactions";
import BaseTable from "@/components/table/BaseTable.vue";
import TransactionControllingDetails from "@/components/accounting/TransactionControllingDetails.vue";
export default {
    title() {
        return this.$t("Controlling");
    },
    components: {
        BaseTable,
        TransactionControllingDetails
    },
    data() {
        return {
            isBusy: false,
            wallets: [],
            wallet: sessionStorage.getItem(sessionStoragePrefix + ".wallet")
                ? sessionStorage.getItem(sessionStoragePrefix + ".wallet")
                : null,
            dateFrom: sessionStorage.getItem(sessionStoragePrefix + ".dateFrom")
                ? sessionStorage.getItem(sessionStoragePrefix + ".dateFrom")
                : null,
            dateTo: sessionStorage.getItem(sessionStoragePrefix + ".dateTo")
                ? sessionStorage.getItem(sessionStoragePrefix + ".dateTo")
                : null,
            fields: [
                {
                    key: "wallet_name",
                    label: this.$t("Wallet"),
                    class: "fit"
                },
                {
                    key: "details",
                    label: this.$t("Details")
                }
            ]
        };
    },
    computed: {
        walletOptions() {
            return [
                {
                    value: null,
                    text: this.$t("All wallets")
                },
                ...this.wallets.map(e => ({
                    value: e.id,
                    text: e.name
                }))
            ];
        }
    },
    watch: {
        wallet() {
            this.refresh();
        },
        dateFrom() {
            this.refresh();
        },
        dateTo() {
            this.refresh();
        }
    },
    async created() {
        this.isBusy = true;
        try {
            this.wallets = (await walletsApi.list()).data;
        } catch (ex) {
            alert(ex);
        }
        this.isBusy = false;
    },
    methods: {
        refresh() {
            this.$refs.table.refresh();
        },
        async fetchData(ctx) {
            if (this.wallet) {
                ctx.wallet = this.wallet;
                sessionStorage.setItem(
                    sessionStoragePrefix + ".wallet",
                    this.wallet
                );
            } else {
                sessionStorage.removeItem(sessionStoragePrefix + ".wallet");
            }
            if (this.dateFrom) {
                ctx.from = this.dateFrom;
                sessionStorage.setItem(
                    sessionStoragePrefix + ".dateFrom",
                    this.dateFrom
                );
            } else {
                sessionStorage.removeItem(sessionStoragePrefix + ".dateFrom");
            }
            if (this.dateTo) {
                ctx.to = this.dateTo;
                sessionStorage.setItem(
                    sessionStoragePrefix + ".dateTo",
                    this.dateTo
                );
            } else {
                sessionStorage.removeItem(sessionStoragePrefix + ".dateTo");
            }
            return await transactionsApi.listControllable(ctx);
        }
    }
};
</script>
