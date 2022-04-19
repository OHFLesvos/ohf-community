<template>
    <BaseWidget :title="$t('Accounting')" icon="money-bill-alt" :to="{name: 'accounting.index'}">
        <b-list-group flush class="border-top">
            <template v-if="data.wallets.length > 0">
                <b-list-group-item v-for="wallet in data.wallets" :key="wallet.id" :to="{ name: 'accounting.transactions.index', query: { wallet: wallet.id } }">
                    {{ wallet.name }}
                    <span class="float-right">{{ wallet.amount_formatted }}</span>
                </b-list-group-item>
            </template>
            <b-list-group-item v-else>
                {{ $t('No wallets found.') }}
            </b-list-group-item>
        </b-list-group>
    </BaseWidget>
</template>
<script>
import BaseWidget from "@/components/dashboard/BaseWidget"
export default {
    components: {
        BaseWidget,
    },
    props: {
        data: { required: true, type: Object },
    },
};
</script>
