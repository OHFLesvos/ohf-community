<template>
    <div v-if="loaded">
        <div v-if="widgets.length > 0" class="card-columns">
            <VisitorsWidget v-if="data.visitors" :data="data.visitors"/>
            <CommunityVolunteersWidget v-if="data.cmtyvol" :data="data.cmtyvol"/>
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
import VisitorsWidget from "@/components/dashboard/VisitorsWidget"
import CommunityVolunteersWidget from "@/components/dashboard/CommunityVolunteersWidget"
export default {
    title() {
        return this.$t("Dashboard");
    },
    components: {
      VisitorsWidget,
      CommunityVolunteersWidget
    },
    data() {
        return {
            loaded: false,
            widgets: [],
            data: []
        };
    },
    async created() {
        let data = await dashboardApi.list()
        this.widgets = data.widgets;
        this.data = data.data;
        this.loaded = true;
        console.log(this.data)
    }
};
</script>
