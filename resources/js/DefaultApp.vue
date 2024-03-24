<template>
    <div v-if="loaded" class="site-wrapper h-100">
        <div class="site-canvas h-100">
            <!-- Side navigation -->
            <b-sidebar
                id="sidebar-backdrop"
                no-header
                backdrop
                shadow
                width="230"
            >
                <SideNav :appName="appName" :appVersion="appVersion" :appEnv="appEnv"/>
            </b-sidebar>

            <!-- Main -->
            <main class="d-flex flex-column h-100">
                <!-- Site header -->
                <header class="site-header">
                    <SiteHeader :appName="appName"/>
                </header>
                <!-- Content -->
                <article class="site-content bg-light">
                    <div class="">

                        <!-- Content -->
                        <div v-if="settings">
                            <router-view name="breadcrumbs" />
                            <router-view name="header" />
                            <router-view name="beforeContent" />
                            <router-view />
                        </div>

                        <!-- Floating action button -->
                        <a v-if="fab"
                            :to="fab.route"
                            class="btn btn-primary btn-lg d-md-none floating-action-button"
                        >
                            <font-awesome-icon :icon="fab.icon"/>
                        </a>
                    </div>
                </article>
            </main>
        </div>
    </div>
    <b-container v-else fluid class="pt-2">
        {{ $t('Loading...') }}
    </b-container>
</template>

<script>
import settingsApi from "@/api/settings";
import userProfileApi from "@/api/userprofile";
import SideNav from "@/components/ui/SideNav.vue"
import SiteHeader from "@/components/ui/SiteHeader.vue"
export default {
    components: {
        SideNav,
        SiteHeader
    },
    data() {
        return {
            settings: null,
            appName: window.Laravel.title,
            appVersion: window.Laravel.appVersion,
            appEnv: window.Laravel.appEnv,
            fab: undefined, // not implemented
            loaded: false,
        };
    },
    async beforeCreate() {
        this.settings = await settingsApi.list();
        this.$store.commit("SET_SETTINGS", this.settings);
        let authenticatedUser = await userProfileApi.authenticatedUser();
        this.$store.commit("SET_AUTHENTICATED_USER", authenticatedUser);
        this.loaded = true;
    }
};
</script>
