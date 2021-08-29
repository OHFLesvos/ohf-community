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
    </b-container>
</template>

<script>
import walletsApi from "@/api/accounting/wallets";
import { can } from "@/plugins/laravel";
export default {
    data() {
        return {
            loaded: false,
            wallets: []
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
