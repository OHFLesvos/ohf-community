<template>
    <div>
        <b-container fluid class="pt-4 pb-0">
            <div class="d-flex justify-content-center flex-wrap">
                <div v-for="button in buttons.filter(b => b.show)" :key="button.text">
                    <b-button
                        :to="button.to"
                        :href="button.href"
                        variant="outline-primary"
                        class="dashboard-button mr-4 mb-3 d-flex flex-column align-items-center justify-content-center">
                        <font-awesome-icon :icon="button.icon" style="font-size: 24px;" class="mb-2" />
                        {{ button.text }}
                    </b-button>
                </div>
            </div>
        </b-container>
        <b-container fluid class="pt-3" v-if="loaded">
            <div v-if="Object.keys(data).length > 0" class="card-columns">
                <VisitorsWidget v-if="data.visitors" :data="data.visitors"/>
                <CommunityVolunteersWidget v-if="data.cmtyvol" :data="data.cmtyvol"/>
                <AccountingWidget v-if="data.accounting" :data="data.accounting"/>
                <FundraisingWidget v-if="data.fundraising" :data="data.fundraising"/>
                <UsersWidget v-if="data.users" :data="data.users"/>
            </div>
            <b-alert v-else variant="info" show>
                {{ $t('There is currently no content available for you here.')  }}
            </b-alert>
        </b-container>
        <b-container v-else fluid class="pt-2">
            {{ $t('Loading...') }}
        </b-container>
</div>
</template>

<script>
import dashboardApi from "@/api/dashboard";
import VisitorsWidget from "@/components/dashboard/VisitorsWidget.vue"
import CommunityVolunteersWidget from "@/components/dashboard/CommunityVolunteersWidget.vue"
import AccountingWidget from "@/components/dashboard/AccountingWidget.vue"
import FundraisingWidget from "@/components/dashboard/FundraisingWidget.vue"
import UsersWidget from "@/components/dashboard/UsersWidget.vue"
export default {
    title() {
        return this.$t("Dashboard");
    },
    components: {
        VisitorsWidget,
        CommunityVolunteersWidget,
        AccountingWidget,
        FundraisingWidget,
        UsersWidget,
    },
    data() {
        return {
            loaded: false,
            data: {},
            buttons: [
                {
                    text: this.$t('Visitors'),
                    icon: 'door-open',
                    to: { name: 'visitors.index' },
                    show: this.can('register-visitors'),
                },
                {
                    text: this.$t('Community volunteers'),
                    icon: 'id-badge',
                    href: this.route('cmtyvol.index'),
                    show: this.can('view-community-volunteers'),
                },
                {
                    text: this.$t('Badges'),
                    icon: 'id-card',
                    to: { name: 'badges.index' },
                    show: this.can('create-badges'),
                },
                {
                    text: this.$t('Accounting'),
                    icon: 'money-bill-alt',
                    to: { name: 'accounting.index' },
                    show: this.can('view-accounting'),
                },
                {
                    text: this.$t('Donation Management'),
                    icon: 'donate',
                    to: { name: 'fundraising.index' },
                    show: this.can('view-fundraising-entities'),
                },
                {
                    text: this.$t('Reports'),
                    icon: 'chart-pie',
                    to: { name: 'reports.index' },
                    show: this.can('view-reports'),
                },
                {
                    text: this.$t('Users & Roles'),
                    icon: 'user-friends',
                    to: { name: 'users.index' },
                    show: this.can('view-user-management'),
                },
                {
                    text: this.$t('Settings'),
                    icon: 'cogs',
                    to: { name: 'settings' },
                    show: true,
                },
                {
                    text: this.$t('System Information'),
                    icon: 'microchip',
                    to: { name: 'system-info' },
                    show: this.can('view-system-information'),
                },
            ]
        };
    },
    async created() {
        let data = await dashboardApi.list()
        this.data = data.data;
        this.loaded = true;
    }
};
</script>
