import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import CurrentVisitorsPage from '@/pages/visitors/CurrentVisitorsPage'
import VisitorReportPage from '@/pages/visitors/VisitorReportPage'
import NotFoundPage from '@/pages/NotFoundPage'

export default new VueRouter({
    mode: 'history',
    base: '/visitors/',
    routes: [
        {
            path: '/',
            redirect: {
                name: 'visitors.listCurrent'
            }
        },
        {
            path: '/current',
            name: 'visitors.listCurrent',
            components: {
                default: CurrentVisitorsPage,
            }
        },
        {
            path: '/report',
            name: 'visitors.report',
            components: {
                default: VisitorReportPage,
            }
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
