<template>
    <b-container class="pt-4 pb-0">
        <div class="d-flex justify-content-start flex-wrap">
            <div v-for="button in availableButtons" :key="button.text">
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
        <b-alert v-if="availableButtons.length == 0" variant="info" show>
            {{ $t('There is currently no content available for you here.')  }}
        </b-alert>
    </b-container>
</template>

<script>
export default {
    title() {
        return this.$t("Dashboard");
    },
    data() {
        return {
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
                    to: { name: 'cmtyvol.index' },
                    show: this.can('view-community-volunteers'),
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
                    text: this.$t('Badges'),
                    icon: 'id-card',
                    to: { name: 'badges.index' },
                    show: this.can('create-badges'),
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
                {
                    text: this.$t('User Profile'),
                    icon: 'user',
                    to: { name: 'userprofile' },
                    show: true,
                },
            ]
        };
    },
    computed: {
        availableButtons() {
            return this.buttons.filter(b => b.show)
        }
    }
};
</script>
