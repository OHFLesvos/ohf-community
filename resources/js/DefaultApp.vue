<template>
    <div v-if="settings">
        <router-view name="breadcrumbs" />
        <router-view name="header" />
        <router-view name="beforeContent" />
        <router-view />
    </div>
    <b-container v-else fluid class="pt-2">
        <!-- <b-spinner/> -->
        {{ $t('Loading...') }}
    </b-container>
</template>

<script>
import settingsApi from "@/api/settings";
export default {
    data() {
        return {
            settings: null
        };
    },
    async beforeCreate() {
        this.settings = await settingsApi.list();
        this.$store.commit("SET_SETTINGS", this.settings);
    }
};
</script>
