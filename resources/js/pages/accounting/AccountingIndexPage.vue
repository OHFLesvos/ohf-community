<template>
    <b-container class="px-0">
        <b-card
            class="shadow-sm mb-4"
            no-body
            header-class="d-flex justify-content-between"
        >
            <template #header>
                <span>{{ $t("Wallets") }}</span>
                <router-link
                    v-if="can('configure-accounting')"
                    :to="{ name: 'accounting.wallets.index' }"
                >
                    {{ $t("Manage wallets") }}
                </router-link>
            </template>
            <b-list-group flush>
                <b-list-group-item v-if="!loaded">
                    {{ $t("Loading...") }}
                </b-list-group-item>
                <template v-else-if="wallets.length > 0">
                    <b-list-group-item
                        v-for="wallet in wallets"
                        :key="wallet.id"
                        :to="
                            can('view-transactions')
                                ? {
                                      name: 'accounting.transactions.index',
                                      params: { wallet: wallet.id }
                                  }
                                : null
                        "
                    >
                        {{ wallet.name }}
                        <span class="float-right">{{
                            wallet.amount | decimalNumberFormat
                        }}</span>
                    </b-list-group-item>
                </template>
                <b-list-group-item v-else>
                    {{ $t("No wallets found.") }}
                </b-list-group-item>
            </b-list-group>
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
import walletsApi from "@/api/accounting/wallets";
import { can } from "@/plugins/laravel";
export default {
    data() {
        return {
            loaded: false,
            wallets: [],
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
                }
            ]
        };
    },
    async created() {
        this.wallets = await walletsApi.names();
        this.loaded = true;
    },
    methods: {
        can
    }
};
</script>
