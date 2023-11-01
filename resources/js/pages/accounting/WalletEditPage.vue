<template>
    <b-container v-if="wallet">
        <wallet-form
            :wallet="wallet"
            :title="$t('Edit wallet')"
            :disabled="isBusy"
            @submit="updateWallet"
            @cancel="handleCancel"
            @delete="deleteWallet"
        />
        <p class="text-right mt-2">
            <small>
                {{ $t("Created") }}:
                {{ wallet.created_at | dateTimeFormat }} </small
            ><br />
            <small>
                {{ $t("Last updated") }}:
                {{ wallet.updated_at | dateTimeFormat }}
            </small>
        </p>
    </b-container>
    <b-container v-else>
        {{ $t("Loading...") }}
    </b-container>
</template>

<script>
import { showSnackbar } from "@/utils";
import walletsApi from "@/api/accounting/wallets";
import WalletForm from "@/components/accounting/WalletForm.vue";
export default {
    title() {
        return this.$t("Edit wallet");
    },
    components: {
        WalletForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            wallet: null,
            isBusy: false
        };
    },
    watch: {
        $route() {
            this.fetchWallet();
        }
    },
    async created() {
        this.fetchWallet();
    },
    methods: {
        async fetchWallet() {
            try {
                let data = await walletsApi.find(this.id);
                this.wallet = data.data;
            } catch (err) {
                alert(err);
            }
        },
        async updateWallet(formData) {
            this.isBusy = true;
            try {
                await walletsApi.update(this.id, formData);
                showSnackbar(this.$t("Wallet updated."));
                this.$router.push({ name: "accounting.wallets.index" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async deleteWallet() {
            this.isBusy = true;
            try {
                await walletsApi.delete(this.id);
                showSnackbar(this.$t("Wallet deleted."));
                this.$router.push({ name: "accounting.wallets.index" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({ name: "accounting.wallets.index" });
        }
    }
};
</script>
