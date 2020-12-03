import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import CommunityVolunteersReportPage from '@/pages/cmtyvol/CommunityVolunteersReportPage'
import CommunityVolunteersOverviewPage from '@/pages/cmtyvol/CommunityVolunteersOverviewPage'
import CmtyvolComments from '@/components/cmtyvol/CmtyvolComments'

import i18n from '@/plugins/i18n'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

Vue.config.productionTip = false

new Vue({
    el: '#cmtyvol-app',
    i18n,
    components: {
        CommunityVolunteersReportPage,
        CommunityVolunteersOverviewPage,
        CmtyvolComments
    }
});
