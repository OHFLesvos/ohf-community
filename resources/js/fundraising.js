import Vue from 'vue'

import FontAwesomeIcon from './components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import DonorsTable from '@/components/fundraising/DonorsTable'
import DonationsTable from '@/components/fundraising/DonationsTable'
import CommentsList from '@/components/comments/CommentsList'
import TagManager from '@/components/tags/TagManager'

import i18n from '@/plugins/i18n'

Vue.config.productionTip = false

new Vue({
    el: '#fundraising-app',
    i18n,
    components: {
        DonationsTable,
        DonorsTable,
        CommentsList,
        TagManager
    }
});
