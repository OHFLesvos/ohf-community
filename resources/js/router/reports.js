import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import NotFoundPage from '@/pages/NotFoundPage'

export default new VueRouter({
    mode: 'history',
    base: '/reports/',
    routes: [
        {
            path: '/',
            redirect: {
                name: 'visitors.listCurrent'
            }
        },
        {
            path: '/visitors/checkins',
            name: 'reports.visitors.checkins',
            components: {
                default: () => import(/* webpackChunkName: "reports" */ '@/pages/reports/VisitorReportPage'),
            }
        },
        {
            path: '/fundraising/donations',
            name: 'reports.fundraising.donations',
            components: {
                default: () => import(/* webpackChunkName: "reports" */ '@/pages/reports/FundraisingReportPage'),
            }
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
