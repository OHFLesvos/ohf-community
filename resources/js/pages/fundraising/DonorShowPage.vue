<template>
    <alert-with-retry
        v-if="error"
        :value="error"
        @retry="fetchData"
    />
    <b-container fluid v-else-if="loaded">
        <PageHeader :title="donor.full_name" :buttons="pageHeaderButtons"/>
        <TabNav :items="tabNavItems">
            <template v-slot:after(donations)>
                <b-badge
                    v-if="donationsCount > 0"
                    class="ml-1"
                >
                    {{ donationsCount }}
                </b-badge>
            </template>
            <template v-slot:after(budgets)>
                <b-badge
                    v-if="budgetsCount > 0"
                    class="ml-1"
                >
                    {{ budgetsCount }}
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
        </TabNav>
        <router-view @count="updateCount" />
    </b-container>
    <b-container v-else>
        {{ $t('Loading...') }}
    </b-container>
</template>

<script>
import donorsApi from '@/api/fundraising/donors'
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import TabNav from '@/components/layout/TabNav.vue'
import PageHeader from "@/components/layout/PageHeader.vue";
export default {
    title() {
        return this.$t("Show donor");
    },
    components: {
        AlertWithRetry,
        TabNav,
        PageHeader
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
            canViewBudgets: false,
            donationsCount: null,
            budgetsCount: null,
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
                    to: { name: 'fundraising.donors.show.budgets' },
                    icon: 'money-bill-alt',
                    text: this.$t('Budgets'),
                    key: 'budgets',
                    show: () => this.canViewBudgets
                },
                {
                    to: { name: 'fundraising.donors.show.comments' },
                    icon: 'comments',
                    text: this.$t('Comments'),
                    key: 'comments'
                },
            ],
            donor: null,
            pageHeaderButtons: [
                {
                    to: {
                        name: "fundraising.donors.edit",
                        params: { id: this.id }
                    },
                    variant: "primary",
                    icon: "pencil-alt",
                    text: this.$t("Edit"),
                    show: this.can("manage-fundraising-entities")
                },
                {
                    href: this.route(
                        "api.fundraising.donors.donations.export",
                        this.id
                    ),
                    icon: "download",
                    text: this.$t("Export"),
                    show: this.can("view-fundraising-entities")
                },
                {
                    href: this.route(
                        "api.fundraising.donors.vcard",
                        this.id
                    ),
                    icon: "address-card",
                    text: this.$t("vCard"),
                    show: this.can("view-fundraising-entities")
                },
            ],
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
                let data = await donorsApi.find(this.id)
                let donor = data.data
                this.canViewDonations = donor.can_view_donations
                this.canViewBudgets = donor.can_view_budgets
                this.donationsCount = donor.donations_count
                this.budgetsCount = donor.budgets_count
                this.commentCount = donor.comments_count
                this.loaded = true
                this.donor = donor
            } catch (err) {
                this.error = err
            }
        },
        updateCount (evt) {
            if (evt.type == 'donations') {
                this.donationsCount = evt.value
            }
            if (evt.type == 'budgets') {
                this.budgetsCount = evt.value
            }
            else if (evt.type == 'comments') {
                this.commentCount = evt.value
            }
        }
    }
}
</script>
