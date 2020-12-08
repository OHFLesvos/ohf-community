import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import VisitorReportPage from '@/pages/reports/VisitorReportPage'
import LibraryReportPage from '@/pages/reports/LibraryReportPage'
import FundraisingReportPage from '@/pages/reports/FundraisingReportPage'
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
                default: VisitorReportPage,
            }
        },
        {
            path: '/library/books',
            name: 'reports.library.books',
            components: {
                default: LibraryReportPage,
            }
        },
        {
            path: '/fundraising/donations',
            name: 'reports.fundraising.donations',
            components: {
                default: FundraisingReportPage,
            }
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
