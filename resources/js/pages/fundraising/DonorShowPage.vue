<template>
    <div>

        <b-tabs content-class="mt-3">

            <!-- Donor -->
            <b-tab
                :title="$t('fundraising.donor')"
                active
            >
                <donor-details :donor="donor" />
            </b-tab>

            <!-- Donations -->
            <b-tab
                v-if="donor.can_create_donation || donor.can_view_donations"
                :title="$t('fundraising.donations')"
            >
                <template v-slot:title>
                    {{ $t('fundraising.donations') }}
                    <b-badge
                        v-if="donor.donations !== null && donor.donations.length > 0"
                        class="d-none d-sm-inline"
                    >
                        {{ donor.donations.length }}
                    </b-badge>
                </template>

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
                    <p v-else>
                        <b-button
                            variant="primary"
                            @click="showForm = true"
                        >
                            <font-awesome-icon icon="plus-circle" />
                            {{ $t('fundraising.register_new_donation') }}
                        </b-button>
                    </p>
                 </template>

                <!-- Existing donations -->
                <template v-if="donor.can_view_donations">
                    <b-row>
                        <b-col lg="9" xl="10">
                            <!-- Individual donations  -->
                            <individual-donations-table
                                v-if="donor.donations"
                                :donor-id="donor.id"
                                :donations="donor.donations"
                                :base-currency="baseCurrency"
                            />
                        </b-col>
                        <b-col lg="3" xl="2">
                            <!-- Donations per year -->
                            <donations-per-year-table
                                v-if="donor.donations_per_year && donor.donations_per_year.length > 0"
                                :donations="donor.donations_per_year"
                                :base-currency="baseCurrency"
                            />
                        </b-col>
                    </b-row>
                </template>

            </b-tab>

            <!-- Comments -->
            <b-tab
                :title="$t('app.comments')"
                lazy
            >
                <template v-slot:title>
                    {{ $t('app.comments') }}
                    <b-badge
                        v-if="commentCount > 0"
                        class="d-none d-sm-inline"
                    >
                        {{ commentCount }}
                    </b-badge>
                </template>
                <comments-list
                    :api-list-url="route('api.fundraising.donors.comments.index', donor.id)"
                    :api-create-url="route('api.fundraising.donors.comments.store', donor.id)"
                    @count="commentCount = $event"
                />
            </b-tab>

        </b-tabs>
    </div>
</template>

<script>
import axios from '@/plugins/axios'
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
            isBusy: false,
            commentCount: 0,
        }
    },
    async created () {
        this.countComments()
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
        },
        async countComments () {
            let url = this.route('api.fundraising.donors.comments.count', this.donor.id)
            try {
                let res = await axios.get(url)
                this.commentCount = parseInt(res.data)
            } catch (err) {
                console.error(err)
            }
        }
    }
}
</script>
