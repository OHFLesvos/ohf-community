<template>
    <b-container
        v-if="wallet"
        fluid
        class="px-0"
    >
        <wallet-form
            :wallet="wallet"
            :disabled="isBusy"
            @submit="updateWallet"
            @cancel="handleCnacel"
            @delete="deleteWallet"
        />
        <hr>
        <p class="text-right">
            <small>
                {{ $t('Last updated') }}:
                {{ dateFormat(wallet.updated_at) }}
            </small>
        </p>
    </b-container>
    <p v-else>
        {{ $t('Loading...') }}
    </p>
</template>

<script>
import moment from 'moment'
import { showSnackbar } from '@/utils'
import walletsApi from '@/api/accounting/wallets'
import WalletForm from '@/components/accounting/WalletForm'
export default {
    components: {
        WalletForm
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            wallet: null,
            isBusy: false
        }
    },
    watch: {
        $route() {
            this.fetchWallet()
        }
    },
    async created () {
        this.fetchWallet()
    },
    methods: {
        async fetchWallet () {
            try {
                let data = await walletsApi.find(this.id)
                this.wallet = data.data
            } catch (err) {
                alert(err)
            }
        },
        async updateWallet (formData) {
            this.isBusy = true
            try {
                await walletsApi.update(this.id, formData)
                showSnackbar(this.$t('Wallet updated.'))
                this.$router.push({ name: 'accounting.wallets.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async deleteWallet () {
            this.isBusy = true
            try {
                await walletsApi.delete(this.id)
                showSnackbar(this.$t('Walled deleted.'))
                this.$router.push({ name: 'accounting.wallets.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCnacel () {
            this.$router.push({ name: 'accounting.wallets.index' })
        },
        dateFormat (value) {
            return moment(value).format('LLL')
        }
    }
}
</script>
