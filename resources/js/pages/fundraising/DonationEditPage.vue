<template>
    <b-container
        v-if="donation"
        class="px-0"
    >
        <b-form-row class="mb-3">
            <b-col sm="auto">
                <strong>{{ $t('Donor') }}:</strong>
            </b-col>
            <b-col>{{ donation.donor }}</b-col>
        </b-form-row>

        <donation-form
            :donation="donation"
            :currencies="currencies"
            :channels="channels"
            :base-currency="baseCurrency"
            :disabled="isBusy"
            @submit="updateDonation"
            @cancel="$router.push({ name: 'fundraising.donations.index' })"
            @delete="deleteDonation"
        />
        <hr>
        <p class="text-right">
            <small>
                {{ $t('Last updated') }}:
                {{ donation.updated_at | dateTimeFormat }}
            </small>
        </p>
    </b-container>
    <p v-else>
        {{ $t('Loading...') }}
    </p>
</template>

<script>
import donationsApi from '@/api/fundraising/donations'
import { showSnackbar } from '@/utils'
import DonationForm from '@/components/fundraising/DonationForm'
export default {
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
