<template>
    <div v-if="loaded">
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
    </div>
    <div v-else>
        {{ $t('Loading...') }}
    </div>
</template>

<script>
import dashboardApi from "@/api/dashboard";
import VisitorsWidget from "@/components/dashboard/VisitorsWidget"
import CommunityVolunteersWidget from "@/components/dashboard/CommunityVolunteersWidget"
import AccountingWidget from "@/components/dashboard/AccountingWidget"
import FundraisingWidget from "@/components/dashboard/FundraisingWidget"
import UsersWidget from "@/components/dashboard/UsersWidget"
export default {
    title() {
        return this.$t("Dashboard");
    },
    components: {
      VisitorsWidget,
      CommunityVolunteersWidget,
      AccountingWidget,
      FundraisingWidget,
      UsersWidget
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
