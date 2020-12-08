import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import VisitorReportPage from '@/pages/reports/VisitorReportPage'
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
            name: 'reports.visitors',
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
