<template>
    <alert-with-retry
        v-if="error"
        :value="error"
        @retry="fetchData"
    />
    <div v-else-if="loaded">
        <tab-nav :items="tabNavItems">
            <template v-slot:after(donations)>
                <b-badge
                    v-if="donationsCount > 0"
                    class="ml-1"
                >
                    {{ donationsCount }}
                </b-badge>
            </template>
            <template v-slot:after(comments)>
                <b-badge
                    v-if="commentCount > 0"
                    class="ml-1"
                >
                    {{ commentCount }}
                </b-badge>
            </template>
        </tab-nav>
        <router-view @count="updateCount" />
    </div>
    <p v-else>
        {{ $t('Loading...') }}
    </p>
</template>

<script>
import donorsApi from '@/api/fundraising/donors'
import AlertWithRetry from '@/components/alerts/AlertWithRetry'
import TabNav from '@/components/layout/TabNav'
export default {
    components: {
        AlertWithRetry,
        TabNav
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            loaded: false,
            error: null,
            canViewDonations: false,
            donationsCount: null,
            commentCount: null,
            tabNavItems: [
                {
                    to: { name: 'fundraising.donors.show' },
                    icon: 'user',
                    text: this.$t('Donor')
                },
                {
                    to: { name: 'fundraising.donors.show.donations' },
                    icon: 'donate',
                    text: this.$t('Donations'),
                    key: 'donations',
                    show: () => this.canViewDonations
                },
                {
                    to: { name: 'fundraising.donors.show.comments' },
                    icon: 'comments',
                    text: this.$t('Comments'),
                    key: 'comments'
                },
            ]
        }
    },
    watch: {
        $route() {
            this.fetchData()
        }
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            this.error = null
            try {
                let data = await donorsApi.find(this.id, true)
                let donor = data.data
                this.canViewDonations = donor.can_view_donations
                this.donationsCount = donor.donations_count
                this.commentCount = donor.comments_count
                this.loaded = true
            } catch (err) {
                this.error = err
            }
        },
        updateCount (evt) {
            if (evt.type == 'donations') {
                this.donationsCount = evt.value
            }
            else if (evt.type == 'comments') {
                this.commentCount = evt.value
            }
        }
    }
}
</script>
