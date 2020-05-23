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
            >
                <template v-slot:header>
                    {{ $t('fundraising.edit_donation') }}
                    <small class="float-right d-none d-sm-inline">
                        {{ $t('app.last_updated') }}
                        {{ dateFormat(selectedDonation.updated_at) }}
                    </small>
                </template>

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

        <!-- Create new donation form -->
        <b-container
            v-else-if="newDonationForm"
            class="px-0"
        >
            <b-card
                :header="$t('fundraising.register_new_donation')"
                class="mb-4"
                body-class="pb-0"
            >
                <donation-form
                    :currencies="currencies"
                    :channels="channels"
                    :base-currency="baseCurrency"
                    :disabled="isBusy"
                    @submit="registerDonation"
                    @cancel="newDonationForm = false"
                />
            </b-card>
        </b-container>

        <template v-else>

            <!-- Register new donation button -->
            <p v-if="canCreate">
                <b-button
                    variant="primary"
                    @click="newDonationForm = true"
                >
                    <font-awesome-icon icon="plus-circle" />
                    {{ $t('fundraising.register_new_donation') }}
                </b-button>
            </p>

            <!-- Existing donations -->
            <template v-if="donations && donations.length > 0">
                <div v-for="year in years" :key="year">
                    <h4>{{ year }}</h4>
                    <individual-donations-table
                        :donations="donations.filter(d => d.year == year)"
                        :base-currency="baseCurrency"
                        @select="editDonation"
                    />
                </div>
                <p>
                    {{ $t('fundraising.total_donations_made') }}:<br>
                    <u><strong>{{ baseCurrency }} {{ numberFormat(totalAmount) }}</strong></u>
                </p>
            </template>
            <b-alert v-else-if="donations" show variant="info">
                {{ $t('fundraising.no_donations_found') }}
            </b-alert>
            <p v-else>
                {{ $t('app.loading') }}
            </p>

        </template>
    </div>
</template>

<script>
import moment from 'moment'
import donationsApi from '@/api/fundraising/donations'
import donorsApi from '@/api/fundraising/donors'
import { showSnackbar } from '@/utils'
import DonationForm from '@/components/fundraising/DonationForm'
import IndividualDonationsTable from '@/components/fundraising/IndividualDonationsTable'
import numeral from 'numeral'
import { roundWithDecimals } from '@/utils'
export default {
    components: {
        DonationForm,
        IndividualDonationsTable
    },
    props: {
        donorId: {
            required: true
        },
        canCreate: Boolean,
        currencies: {
            required: true,
            type: Object
        },
        channels: {
            required: true,
            type: Array
        }
    },
    data () {
        return {
            donations: null,
            baseCurrency: null,
            selectedDonation: null,
            newDonationForm: false,
            isBusy: false
        }
    },
    watch: {
        donations (val) {
            this.$emit('count', val.length)
        }
    },
    computed: {
        years () {
            if (this.donations) {
                return this.donations
                    .map(donation => donation.year)
                    .filter((v, i, a) => a.indexOf(v) === i)
            }
            return []
        },
        totalAmount () {
            let sum = this.donations.reduce((a,b) => a + parseFloat(b.exchange_amount), 0)
            return roundWithDecimals(sum, 2)
        }
    },
    created () {
        this.fetchDonations()
    },
    methods: {
        async fetchDonations () {
            try {
                let data = await donorsApi.listDonations(this.donorId)
                this.donations = data.data.map(donation => ({
                    ...donation,
                    year: moment(donation.date).year()
                }))
                this.baseCurrency = data.meta.base_currency
            } catch (err) {
                alert(err)
            }
        },
        async registerDonation (formData) {
            this.isBusy = true
            try {
                let data = await donationsApi.store(this.donorId, formData)
                showSnackbar(data.message)
                this.newDonationForm = false
                this.fetchDonations()
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async editDonation (donation) {
            this.selectedDonation = donation
        },
        async updateDonation (formData) {
            this.isBusy = true
            try {
                let data = await donationsApi.update(this.selectedDonation.id, formData)
                showSnackbar(data.message)
                this.selectedDonation = false
                this.fetchDonations()
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
                this.fetchDonations()
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        numberFormat (value) {
            return numeral(value).format('0,0.00')
        },
        dateFormat (value) {
            return moment(value).format('LLL')
        }
    }
}
</script>
