<template>
    <b-container>
        <WalletForm
            :title="$t('Create wallet')"
            :disabled="isBusy"
            @submit="registerWallet"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import walletsApi from '@/api/accounting/wallets'
import WalletForm from '@/components/accounting/WalletForm.vue'
export default {
    title() {
        return this.$t("Create wallet");
    },
    components: {
        WalletForm
    },
    data () {
        return {
            isBusy: false
        }
    },
    methods: {
        async registerWallet (formData) {
            this.isBusy = true
            try {
                await walletsApi.store(formData)
                showSnackbar(this.$t('Wallet added.'))
                this.$router.push({ name: 'accounting.wallets.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCancel () {
            this.$router.push({ name: 'accounting.wallets.index' })
        }
    }
}
</script>
