import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

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
                default: () => import(/* webpackChunkName: "visitors" */ '@/pages/visitors/CurrentVisitorsPage'),
            }
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
