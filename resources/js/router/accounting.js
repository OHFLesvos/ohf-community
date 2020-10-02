import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import SuppliersPage from '@/pages/accounting/SuppliersPage'
import NotFoundPage from '@/pages/NotFoundPage'

export default new VueRouter({
    mode: 'history',
    base: '/accounting/',
    routes: [
        {
            path: '/suppliers',
            name: 'accounting.suppliers',
            components: {
                default: SuppliersPage,
            }
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
