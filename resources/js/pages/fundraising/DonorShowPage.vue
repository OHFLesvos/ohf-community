<template>
    <div v-if="donor">

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
    <p v-else>
        {{ $t('app.loading') }}
    </p>
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
        id: {
            required: true,
            type: Number
        }
    },
    data () {
        return {
            donor: null,
            tabIndex: sessionStorage.getItem(`donors.${this.id}.tabIndex`)
                ? parseInt(sessionStorage.getItem(`donors.${this.id}.tabIndex`))
                : 0,
            donationsCount: null,
            commentCount: null
        }
    },
    watch: {
        tabIndex (val) {
            sessionStorage.setItem(`donors.${this.id}.tabIndex`, val)
        }
    },
    async created () {
        try {
            let data = await donorsApi.find(this.id, true)
            this.donor = data.data
            this.donationsCount = this.donor.donations_count
            this.commentCount = this.donor.comments_count
        } catch (err) {
            alert(err)
        }
    },
    methods: {
        listComments () {
            return donorsApi.listComments(this.id)
        },
        storeComment (data) {
            return donorsApi.storeComment(this.id, data)
        }
    }
}
</script>
