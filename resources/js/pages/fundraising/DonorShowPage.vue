<template>
    <div>
        <b-row>

            <!-- Donor -->
            <b-col
                md
                class="mb-4"
            >
                <donor-details :donor="donor" />

                <!-- Comments -->
                <comments-list
                    :api-list-url="route('api.fundraising.donors.comments.index', donor.id)"
                    :api-create-url="route('api.fundraising.donors.comments.store', donor.id)"
                />

            </b-col>

            <!-- Donations -->
            <b-col
                v-if="donor.can_create_donation || donor.can_view_donations"
                md
                class="mb-4"
            >

                <h3>{{ $t('fundraising.donations') }}</h3>

                <!-- Register new donation -->
                <template v-if="donor.can_create_donation">
                    <b-card
                        v-if="showForm"
                        :header="$t('fundraising.register_new_donation')"
                        class="mb-4"
                        body-class="pb-0"
                    >
                        <donation-register-form
                            :currencies="currencies"
                            :channels="channels"
                            :base-currency="baseCurrency"
                            :disabled="isBusy"
                            @submit="registerDonation"
                            @cancel="showForm = false"
                        />
                    </b-card>
                    <b-button
                        v-else
                        variant="primary"
                        @click="showForm = true"
                    >
                        <font-awesome-icon icon="plus-circle" />
                        {{ $t('fundraising.register_new_donation') }}
                    </b-button>
                 </template>

                <!-- Existing donations -->
                <template v-if="donor.can_view_donations">
                    <!-- Individual donations  -->
                    <individual-donations-table
                        v-if="donor.donations"
                        :donor-id="donor.id"
                        :donations="donor.donations"
                        :base-currency="baseCurrency"
                        />

                    <!-- Donations per year -->
                    <donations-per-year-table
                        v-if="donor.donations_per_year && donor.donations_per_year.length > 0"
                        :donations="donor.donations_per_year"
                        :base-currency="baseCurrency"
                    />
                </template>

            </b-col>
        </b-row>
    </div>
</template>

<script>
import DonorDetails from '@/components/fundraising/DonorDetails'
import DonationRegisterForm from '@/components/fundraising/DonationRegisterForm'
import IndividualDonationsTable from '@/components/fundraising/IndividualDonationsTable'
import DonationsPerYearTable from '@/components/fundraising/DonationsPerYearTable'
import CommentsList from '@/components/comments/CommentsList'
import donationsApi from '@/api/fundraising/donations'
import { showSnackbar } from '@/utils'
export default {
    components: {
        DonorDetails,
        DonationRegisterForm,
        IndividualDonationsTable,
        DonationsPerYearTable,
        CommentsList
    },
    props: {
        donor: {
            required: true,
            type: Object
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
            showForm: false,
            isBusy: false
        }
    },
    methods: {
        async registerDonation (formData) {
            this.isBusy = true
            try {
                let data = await donationsApi.store(this.donor.id, formData)
                showSnackbar(data.message)
                this.showForm = false
                window.location.href = this.route('fundraising.donors.show', this.donor.id)
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        }
    }
}
</script>
