<template>
    <div v-if="authenticatedUser" class="h-100 d-flex flex-column">
        <header class="side-nav-header">
            <!-- Logo -->
            <div class="px-3 pt-3">
                <span class="navbar-brand text-wrap">
                    <img v-if="settings['branding.signet_file']" :src="settings['branding.signet_file']" alt="Brand"/>
                    {{ appName }}
                </span>
            </div>

            <!-- Navigation -->
            <ul class="nav flex-column nav-pills my-3 mt-0">
                <li v-for="n in nav" :key="n.route.name" class="nav-item">
                    <router-link v-if="n.authorized" class="nav-link rounded-0" :class="{'active': n.isActive($route.name)}" :to="n.route">
                        <font-awesome-icon :icon="n.icon" fixed-width />
                        {{ n.caption }}
                    </router-link>
                </li>
            </ul>

        </header>

        <!-- Footer -->
        <footer class="side-nav-footer">

            <hr>
            <div class="text-center">
                <router-link :to="{name: 'userprofile'}" class="d-block mb-1">
                        <UserAvatar
                            :value="authenticatedUser.name"
                            :src="authenticatedUser.avatar_url"
                            size="80px"
                        />
                </router-link>
                {{ authenticatedUser.name }}
            </div>

            <!-- Logout -->
            <div class="px-3 mt-3">
                <button type="button" class="btn btn-block btn-secondary" @click="postRequest(logoutUrl, {})"><font-awesome-icon icon="right-from-bracket"/> {{ $t('Logout') }}</button>
            </div>

            <hr>
            <p class="copyright text-muted px-3">
                {{ appName }}<br>
                <template v-if="appVersion">
                Version: {{ appVersion }}<br>
                </template>
                Environment: {{ appEnv }}<br><br>

                This application is based on <a href="https://github.com/OHFLesvos/ohf-community" target="_blank">open-source software</a> developed by <a href="https://ohf.gr" target="_blank">One Happy Family</a>.
            </p>
        </footer>
    </div>
</template>

<script>
import { postRequest } from "@/utils/form";
import UserAvatar from "@/components/user_management/UserAvatar.vue";
import { mapState } from "vuex";
export default {
    components: {
        UserAvatar
    },
    props: {
        appName: {
            type: String
        },
        appVersion: {
            type: String
        },
        appEnv: {
            type: String
        },
    },
    data() {
        return {
            logoutUrl: this.route('logout'),
            nav: [
                {
                    caption: this.$t('Dashboard'),
                    route: {name: 'dashboard'},
                    icon: 'home',
                    isActive: name => name == 'dashboard',
                    authorized: true,
                },
                {
                    caption: this.$t('Visitors'),
                    route: {name: 'visitors.check_in'},
                    icon: 'door-open',
                    authorized: this.can('register-visitors'),
                    isActive: name => name.startsWith('visitors'),
                },
                {
                    caption: this.$t('Community Volunteers'),
                    route: {name: 'cmtyvol.index'},
                    icon: 'id-badge',
                    authorized: this.can('view-community-volunteers'),
                    isActive: name => name.startsWith('cmtyvol'),
                },
                {
                    caption: this.$t('Accounting'),
                    route: {name: 'accounting.index'},
                    icon: 'money-bill-alt',
                    authorized: this.can('view-transactions'),
                    isActive: name => name.startsWith('accounting'),
                },
                {
                    caption: this.$t('Donation Management'),
                    route: {name: 'fundraising.index'},
                    icon: 'donate',
                    authorized: this.can('view-fundraising'),
                    isActive: name => name.startsWith('fundraising'),
                },
                {
                    caption: this.$t('Badges'),
                    route: {name: 'badges.index'},
                    icon: 'id-card',
                    authorized: this.can('create-badges'),
                    isActive: name => name.startsWith('badges'),
                },
                {
                    caption: this.$t('Reports'),
                    route: {name: 'reports.index'},
                    icon: 'chart-bar',
                    authorized: this.can('view-reports'),
                    isActive: name => name.startsWith('reports'),
                },
                {
                    caption: this.$t('Users & Roles'),
                    route: {name: 'users.index'},
                    icon: 'user-friends',
                    authorized: this.can('view-user-management'),
                    isActive: name => name.startsWith('users') ||  name.startsWith('roles'),
                },
                {
                    caption: this.$t('Settings'),
                    route: {name: 'settings'},
                    icon: 'cogs',
                    authorized: true,
                    isActive: name => name.startsWith('settings'),
                },
            ],
        }
    },
    computed: {
        ...mapState(["settings", "authenticatedUser"]),
    },
    methods: {
        postRequest,
    }
}
</script>

