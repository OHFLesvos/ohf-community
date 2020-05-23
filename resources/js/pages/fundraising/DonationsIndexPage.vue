<template>
    <div>

        <!-- Edit donation form -->
        <b-container
            v-if="selectedDonation"
            class="px-0"
        >
            <b-card
                class="mb-4"
                body-class="pb-0"
                header-class="d-flex justify-content-between align-items-center"
            >
                <template v-slot:header>
                    {{ $t('fundraising.edit_donation') }}
                    <small class="d-none d-sm-inline">
                        {{ $t('app.last_updated') }}:
                        {{ dateFormat(selectedDonation.updated_at) }}
                    </small>
                </template>

                <p>{{ $t('fundraising.donor') }}: {{ selectedDonation.donor }}</p>

                <donation-form
                    :donation="selectedDonation"
                    :currencies="currencies"
                    :channels="channels"
                    :base-currency="baseCurrency"
                    :disabled="isBusy"
                    @submit="updateDonation"
                    @cancel="selectedDonation = false"
                    @delete="deleteDonation"
                />
            </b-card>
        </b-container>

        <!-- Donations table -->
        <donations-table
            v-else
            @select="selectDonation"
            @select-donor="selectDonor"
        />
    </div>
</template>

<script>
import moment from 'moment'
import DonationsTable from '@/components/fundraising/DonationsTable'
import donationsApi from '@/api/fundraising/donations'
import { showSnackbar } from '@/utils'
import DonationForm from '@/components/fundraising/DonationForm'
export default {
    components: {
        DonationsTable,
        DonationForm
    },
    data () {
        return {
            selectedDonation: null,
            baseCurrency: null,
            currencies: {},
            channels: [],
            isBusy: false

        }
    },
    watch: {
        async selectedDonation (val, oldVal) {
            if (!oldVal) {
                this.fetchChannels()
                this.fetchCurrencies()
            }
        }
    },
    methods: {
        selectDonation (donation) {
            this.selectedDonation = donation
        },
        selectDonor (donorId) {
            window.location.href = this.route('fundraising.donors.show', donorId)
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
                let data = await donationsApi.update(this.selectedDonation.id, formData)
                showSnackbar(data.message)
                this.selectedDonation = false
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async deleteDonation () {
            this.isBusy = true
            try {
                let data = await donationsApi.delete(this.selectedDonation.id)
                showSnackbar(data.message)
                this.selectedDonation = false
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        dateFormat (value) {
            return moment(value).format('LLL')
        }
    }
}
</script>
