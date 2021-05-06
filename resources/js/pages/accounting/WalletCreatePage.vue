<template>
    <b-container
        class="px-0"
    >
        <wallet-form
            :disabled="isBusy"
            @submit="registerWallet"
            @cancel="handleCnacel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import walletsApi from '@/api/accounting/wallets'
import WalletForm from '@/components/accounting/WalletForm'
export default {
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
                let data = await walletsApi.store(formData)
                showSnackbar(this.$t('Wallet added.'))
                this.$router.push({ name: 'accounting.wallets.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCnacel () {
            this.$router.push({ name: 'accounting.wallets.index' })
        }
    }
}
</script>
