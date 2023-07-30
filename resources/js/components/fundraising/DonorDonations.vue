<template>
    <div class="mt-3">
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
                    {{ $t('Edit donation') }}
                    <small class="d-none d-sm-inline">
                        {{ $t('Last updated') }}:
                        {{ selectedDonation.updated_at | dateTimeFormat }}
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
                :header="$t('Register new donation')"
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
                    {{ $t('Register new donation') }}
                </b-button>
            </p>

            <!-- Existing donations table -->
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
                    {{ $t('Total donations made') }}:<br>
                    <u><strong>{{ baseCurrency }} {{ totalAmount | decimalNumberFormat }}</strong></u>
                </p>
            </template>
            <b-alert v-else-if="donations" show variant="info">
                {{ $t('No donations found.') }}
            </b-alert>
            <p v-else>
                {{ $t('Loading...') }}
            </p>

        </template>
    </div>
</template>

<script>
import moment from 'moment'
import donationsApi from '@/api/fundraising/donations'
import donorsApi from '@/api/fundraising/donors'
import { showSnackbar } from '@/utils'
import DonationForm from '@/components/fundraising/DonationForm.vue'
import IndividualDonationsTable from '@/components/fundraising/IndividualDonationsTable.vue'
import { roundWithDecimals } from '@/utils'
export default {
    components: {
        DonationForm,
        IndividualDonationsTable
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            donations: null,
            canCreate: false,
            selectedDonation: null,
            newDonationForm: false,
            isBusy: false,
            baseCurrency: null,
            currencies: {},
            channels: []
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
    watch: {
        $route() {
            this.fetchDonations()
        },
        donations (val) {
            this.$emit('count', { type: 'donations', value: val.length })
        },
        async newDonationForm (val, oldVal) {
            if (!oldVal) {
                this.fetchChannels()
                this.fetchCurrencies()
            }
        },
        async selectedDonation (val, oldVal) {
            if (!oldVal) {
                this.fetchChannels()
                this.fetchCurrencies()
            }
        }
    },
    created () {
        this.fetchDonations()
    },
    methods: {
        async fetchDonations () {
            try {
                let data = await donorsApi.listDonations(this.id)
                this.donations = data.data.map(donation => ({
                    ...donation,
                    year: moment(donation.date).year()
                }))
                this.baseCurrency = data.meta.base_currency
                this.canCreate = data.meta.can_create
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
            } catch (err) {
                alert(err)
            }
        },
        async registerDonation (formData) {
            this.isBusy = true
            try {
                let data = await donorsApi.storeDonation(this.id, formData)
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
        }
    }
}
</script>
