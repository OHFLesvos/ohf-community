<template>
    <div>

        <b-tabs
            v-model="tabIndex"
            content-class="mt-3"
        >

            <!-- Donor -->
            <b-tab
                :title="$t('fundraising.donor')"
                lazy
            >
                <donor-details :donor="donor" />
            </b-tab>

            <!-- Donations -->
            <b-tab
                v-if="donor.can_view_donations"
                :title="$t('fundraising.donations')"
                lazy
            >
                <template v-slot:title>
                    {{ $t('fundraising.donations') }}
                    <b-badge
                        v-if="donationsCount > 0"
                        class="d-none d-sm-inline"
                    >
                        {{ donationsCount }}
                    </b-badge>
                </template>

                <donor-donations
                    :donorId="donor.id"
                    :currencies="currencies"
                    :channels="channels"
                    :can-create="donor.can_create_donation"
                    @count="donationsCount = $event"
                />
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
                    :api-list-method="listComments"
                    :api-create-method="donor.can_create_comment ? storeComment : null"
                    @count="commentCount = $event"
                />
            </b-tab>

        </b-tabs>
    </div>
</template>

<script>
import DonorDetails from '@/components/fundraising/DonorDetails'
import DonorDonations from '@/components/fundraising/DonorDonations'
import CommentsList from '@/components/comments/CommentsList'
import donorsApi from '@/api/fundraising/donors'
export default {
    components: {
        DonorDetails,
        DonorDonations,
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
        }
    },
    data () {
        return {
            tabIndex: sessionStorage.getItem(`donors.${this.donor.id}.tabIndex`)
                ? parseInt(sessionStorage.getItem(`donors.${this.donor.id}.tabIndex`))
                : 0,
            donationsCount: this.donor.donations_count,
            commentCount: this.donor.comments_count,
        }
    },
    watch: {
        tabIndex (val) {
            sessionStorage.setItem(`donors.${this.donor.id}.tabIndex`, val)
        }
    },
    methods: {
        listComments () {
            return donorsApi.listComments(this.donor.id)
        },
        storeComment (data) {
            return donorsApi.storeComment(this.donor.id, data)
        }
    }
}
</script>
