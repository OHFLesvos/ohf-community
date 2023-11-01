<template>
    <div>
        <BreadcrumbsNav :items="[]"/>
        <b-container fluid class="pt-3" v-if="loaded">
            <div v-if="Object.keys(data).length > 0" class="card-columns">
                <VisitorsWidget v-if="data.visitors" :data="data.visitors"/>
                <CommunityVolunteersWidget v-if="data.cmtyvol" :data="data.cmtyvol"/>
                <AccountingWidget v-if="data.accounting" :data="data.accounting"/>
                <FundraisingWidget v-if="data.fundraising" :data="data.fundraising"/>
                <UsersWidget v-if="data.users" :data="data.users"/>
                <SystemInfoWidget v-if="data.system" :data="data.system"/>
            </div>
            <b-alert v-else variant="info" show>
                {{ $t('There is currently no content available for you here.')  }}
            </b-alert>
        </b-container>
        <b-container v-else fluid class="pt-2">
            <!-- <b-spinner/> -->
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
import SystemInfoWidget from "@/components/dashboard/SystemInfoWidget.vue"
import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";
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
      SystemInfoWidget,
      BreadcrumbsNav,
    },
    data() {
        return {
            loaded: false,
            data: {}
        };
    },
    async created() {
        let data = await dashboardApi.list()
        this.data = data.data;
        this.loaded = true;
    }
};
</script>
