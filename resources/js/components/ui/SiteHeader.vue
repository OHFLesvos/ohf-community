<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between row m-0 px-0">
        <template>
            <div class="col-auto d-block d-md-none pr-1 pr-sm-3">
                <!-- Back button -->
                <a v-if="backButtonRoute" :to="backButtonRoute" class="btn btn-link text-light">
                    <font-awesome-icon icon="arrow-left"/>
                </a>
                <!-- Sidebar navigation toggle -->
                <b-button v-b-toggle.sidebar-backdrop variant="link" class="text-light">
                    <font-awesome-icon icon="bars"/>
                </b-button>
            </div>
            <b-button v-b-toggle.sidebar-backdrop variant="link" class="text-light d-none d-md-inline-block ml-3">
                <font-awesome-icon icon="bars"/>
            </b-button>
        </template>

        <!-- Brand -->
        <div class="col-auto px-0 px-sm-3">

            <!-- Logo, Name -->
            <router-link class="navbar-brand d-none d-md-inline-block" :to="{name: 'dashboard'}">
                <img v-if="settings['branding.signet_file']" :src="settings['branding.signet_file']" alt="Brand" /> {{ appName }}
            </router-link>
            <!-- Title -->
            <span v-if="title" class="text-light ml-md-4">{{ title }}</span>
        </div>

        <!-- Right side -->
        <div class="col text-right">
            <template v-if="authenticatedUser">
                <router-link :to="{name: 'userprofile'}" class="mr-2">
                    <UserAvatar
                        :value="authenticatedUser.name"
                        :src="authenticatedUser.avatar_url"
                        size="32px"
                    />
                </router-link>
                <b-button @click="postRequest(logoutUrl, {})" class="btn btn-dark">
                    <font-awesome-icon icon="right-from-bracket" class="mr-md-1"/>
                    <span class="d-none d-md-inline">{{ $t('Logout') }}</span>
                </b-button>
            </template>
            <template v-else>
                <a :href="loginUrl" class="btn btn-dark">
                    <font-awesome-icon icon="right-to-bracket" class="mr-md-1"/>
                    <span class="d-none d-md-inline">{{ $t('Login') }}</span>
                </a>
            </template>
        </div>
    </nav>
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
        }
    },
    data() {
        return {
            backButtonRoute: undefined,
            logoutUrl: this.route('logout'),
            loginUrl: this.route('login'),
        }
    },
    computed: {
        ...mapState(["title", "settings", "authenticatedUser"]),
    },
    methods: {
        postRequest,
    }
}
</script>
