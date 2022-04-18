<template>
    <div v-if="loaded">
        <div v-if="widgets.length > 0" class="card-columns">
            <div v-for="(widget, idx) in widgets" :key="idx" v-html="widget">
            </div>
        </div>
        <b-alert v-else variant="info" show>
            {{ $t('There is currently no content available for you here.')  }}
        </b-alert>
    </div>
    <div v-else>
        {{ $t('Loading...') }}
    </div>
</template>

<script>
import dashboardApi from "@/api/dashboard";
export default {
    title() {
        return this.$t("Dashboard");
    },
    data() {
        return {
            loaded: false,
            widgets: []
        };
    },
    async created() {
        this.widgets = await dashboardApi.list();
        this.loaded = true;
    }
};
</script>
