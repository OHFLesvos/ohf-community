<template>
    <b-container class="px-0">
        <alert-with-retry :value="errorText" @retry="refresh" />
        <b-card :header="$t('Wallets')" class="shadow-sm mb-4" no-body>
            <b-table
                ref="table"
                :items="fetchData"
                :fields="fields"
                show-empty
                :empty-text="$t('No wallets found.')"
                class="mb-0"
                thead-class="d-none"
                responsive
            >
                <div slot="table-busy" class="text-center my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>{{ $t("Loading...") }}</strong>
                </div>
                <template #cell(name)="data">
                    <template v-if="can('view-transactions')">
                        <router-link
                            :to="{
                                name: 'accounting.transactions.index',
                                params: { wallet: data.item.id }
                            }"
                        >
                            {{ data.value }}</router-link
                        >
                    </template>
                    <template v-else>
                        {{ data.value }}
                    </template>
                </template>
            </b-table>
        </b-card>
        <b-row>
            <b-col
                v-for="(button, idx) in buttons.filter(btn => btn.show)"
                :key="idx"
                sm="6"
                md="4"
                lg="3"
                class="mb-4"
            >
                <b-button
                    class="d-block"
                    :variant="button.variant"
                    :to="button.to"
                >
                    <font-awesome-icon :icon="button.icon" />
                    {{ button.text }}</b-button
                >
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
import walletsApi from "@/api/accounting/wallets";
export default {
    title() {
        return this.$t("Accounting");
    },
    components: {
        AlertWithRetry
    },
    data() {
        return {
            errorText: null,
            fields: [
                {
                    key: "name",
                    label: this.$t("Name")
                },
                {
                    key: "amount_formatted",
                    label: this.$t("Amount"),
                    class: "fit text-right",
                    tdClass: (value, key, item) =>
                        item.amount < 0 ? "text-danger" : null
                }
            ],
            buttons: [
                {
                    to: {
                        name: "accounting.transactions.summary"
                    },
                    icon: "calculator",
                    text: this.$t("Summary"),
                    show: this.can("view-accounting-summary")
                },
                {
                    to: { name: "accounting.categories.index" },
                    icon: "tag",
                    text: this.$t("Categories"),
                    show: this.can("view-accounting-categories")
                },
                {
                    to: { name: "accounting.projects.index" },
                    icon: "project-diagram",
                    text: this.$t("Projects"),
                    show: this.can("view-accounting-projects")
                },
                {
                    to: { name: "accounting.suppliers.index" },
                    variant: "secondary",
                    icon: "truck",
                    text: this.$t("Suppliers"),
                    show: this.can("view-suppliers") || can("manage-suppliers")
                },
                {
                    to: { name: "accounting.budgets.index" },
                    variant: "secondary",
                    icon: "money-bill-alt",
                    text: this.$t("Budgets"),
                    show: this.can("view-budgets") || can("manage-budgets")
                },
                {
                    to: { name: "accounting.wallets.index" },
                    variant: "secondary",
                    icon: "wallet",
                    text: this.$t("Wallets"),
                    show: this.can("configure-accounting")
                }
            ]
        };
    },
    methods: {
        async fetchData() {
            this.errorText = null;
            try {
                return await walletsApi.names();
            } catch (ex) {
                this.errorText = ex;
            }
        },
        refresh() {
            this.$refs.table.refresh();
        }
    }
};
</script>
