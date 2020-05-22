<template>
    <div>
        <!-- Register new donation -->
        <template v-if="donor.can_create_donation">
            <donation-register-form-modal
                ref="modal"
                :currencies="currencies"
                :channels="channels"
                :base-currency="baseCurrency"
                :disabled="isBusy"
                @submit="registerDonation"
            />
        </template>

        <!-- Existing donations -->
        <template v-if="donor.can_view_donations">
            <template v-if="donations && donations.length > 0">
                <div v-for="year in years" :key="year">
                    <h3>{{ year }}</h3>
                    <individual-donations-table
                        :donor-id="donor.id"
                        :donations="donations.filter(d => d.year == year)"
                        :base-currency="baseCurrency"
                    />
                </div>
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
import donationsApi from '@/api/fundraising/donations'
import donorsApi from '@/api/fundraising/donors'
import { showSnackbar } from '@/utils'
import DonationRegisterFormModal from '@/components/fundraising/DonationRegisterFormModal'
import IndividualDonationsTable from '@/components/fundraising/IndividualDonationsTable'
export default {
    components: {
        DonationRegisterFormModal,
        IndividualDonationsTable
    },
    props: {
        donor: {
            type: Object,
            required: true
        },
        currencies: {
            required: true,
            type: Object
        },
        channels: {
            required: true,
            type: Array
        },
        baseCurrency: {
            required: true,
            type: String
        }
    },
    data () {
        return {
            donations: null,
            showForm: false,
            isBusy: false
        }
    },
    watch: {
        donations (val) {
            this.$emit('count', val.length)
        }
    },
    created () {
        this.fetchDonations()
    },
    computed: {
        years () {
            if (this.donations) {
                return this.donations
                    .map(donation => donation.year)
                    .filter((v, i, a) => a.indexOf(v) === i)
            }
            return []
        }
    },
    methods: {
        async fetchDonations () {
            try {
                let data = await donorsApi.listDonations(this.donor.id)
                this.donations = data.data
            } catch (err) {
                alert(err)
            }
        },
        async registerDonation (formData) {
            this.isBusy = true
            try {
                let data = await donationsApi.store(this.donor.id, formData)
                this.$refs.modal.close()
                showSnackbar(data.message)
                this.showForm = false
                this.fetchDonations()
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        }
    }
}
</script>