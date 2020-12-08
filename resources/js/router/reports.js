import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import VisitorReportPage from '@/pages/reports/VisitorReportPage'
import LibraryReportPage from '@/pages/reports/LibraryReportPage'
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
            // props: {
            //     header: {
            //         title: i18n.t('app.report'),
            //         buttons: [
            //             {
            //                 click: () => router.back(),
            //                 icon: 'times-circle',
            //                 text: i18n.t('app.close'),
            //             }
            //         ]
            //     }
            // }
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
