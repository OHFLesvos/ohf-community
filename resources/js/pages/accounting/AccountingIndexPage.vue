<template>
    <b-container class="px-0">
        <b-card :header="$t('Wallets')" class="shadow-sm mb-4" no-body>
            <b-list-group flush>
                <template v-if="wallets.length > 0">
                    <template v-if="can('view-transactions')">
                        <b-list-group-item
                            v-for="wallet in wallets"
                            :key="wallet.id"
                            :to="{
                                name: 'accounting.transactions.index',
                                params: { wallet: wallet.id }
                            }"
                        >
                            {{ wallet.name }}
                            <span class="float-right">{{
                                formatNumber(wallet.amount)
                            }}</span>
                        </b-list-group-item>
                    </template>
                    <template v-else>
                        <b-list-group-item
                            v-for="wallet in wallets"
                            :key="wallet.id"
                        >
                            {{ wallet.name }}
                        </b-list-group-item>
                    </template>
                </template>
                <b-list-group-item v-else>
                    {{ $t("No wallets found.") }}
                </b-list-group-item>
            </b-list-group>
        </b-card>
    </b-container>
</template>

<script>
import numeral from "numeral";
import walletsApi from "@/api/accounting/wallets";
import { can } from "@/plugins/laravel";
export default {
    data() {
        return {
            wallets: []
        };
    },
    async created() {
        // TODO handle pagination
        this.wallets = (await walletsApi.list()).data;
    },
    methods: {
        can,
        formatNumber: value => numeral(value).format("0,0.00")
    }
};
</script>
