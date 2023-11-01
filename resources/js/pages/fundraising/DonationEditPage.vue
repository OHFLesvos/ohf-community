<template>
    <b-container v-if="donation">
        <DonationForm
            :donation="donation"
            :currencies="currencies"
            :channels="channels"
            :base-currency="baseCurrency"
            :disabled="isBusy"
            :title="$t('Edit donation of {name}', { name: donation.donor })"
            @submit="updateDonation"
            @cancel="$router.push({ name: 'fundraising.donations.index' })"
            @delete="deleteDonation"
        />
        <p class="text-right">
            <small>
                {{ $t('Last updated') }}:
                {{ donation.updated_at | dateTimeFormat }}
            </small>
        </p>
    </b-container>
    <b-container v-else>
        {{ $t('Loading...') }}
    </b-container>
</template>

<script>
import donationsApi from '@/api/fundraising/donations'
import { showSnackbar } from '@/utils'
import DonationForm from '@/components/fundraising/DonationForm.vue'
export default {
    title() {
        return this.$t("Edit donation");
    },
    components: {
        DonationForm
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            donation: null,
            baseCurrency: null,
            currencies: {},
            channels: [],
            isBusy: false
        }
    },
    created () {
        this.fetchDonation()
        this.fetchChannels()
        this.fetchCurrencies()
    },
    methods: {
        async fetchDonation () {
            try {
                let data = await donationsApi.find(this.id)
                this.donation = data.data
            } catch (err) {
                alert(err)
            }
        },
        async fetchChannels () {
            try {
                let data = await donationsApi.listChannels()
                this.channels = data.data
            } catch (err) {
                alert(err)
            }
        },
        async fetchCurrencies () {
            try {
                let data = await donationsApi.listCurrencies()
                this.currencies = data.data
                this.baseCurrency = data.meta.base_currency
            } catch (err) {
                alert(err)
            }
        },
        async updateDonation (formData) {
            this.isBusy = true
            try {
                let data = await donationsApi.update(this.id, formData)
                showSnackbar(data.message)
                this.$router.push({ name: 'fundraising.donations.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async deleteDonation () {
            this.isBusy = true
            try {
                let data = await donationsApi.delete(this.id)
                showSnackbar(data.message)
                this.$router.push({ name: 'fundraising.donations.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        }
    }
}
</script>
