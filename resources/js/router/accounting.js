import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import SuppliersIndexPage from '@/pages/accounting/SuppliersIndexPage'
import SupplierCreatePage from '@/pages/accounting/SupplierCreatePage'
import SupplierViewPage from '@/pages/accounting/SupplierViewPage'
import SupplierEditPage from '@/pages/accounting/SupplierEditPage'
import NotFoundPage from '@/pages/NotFoundPage'

export default new VueRouter({
    mode: 'history',
    base: '/accounting/',
    routes: [
        {
            path: '/suppliers',
            name: 'accounting.suppliers.index',
            component: SuppliersIndexPage
        },
        {
            path: '/suppliers/create',
            name: 'accounting.suppliers.create',
            component: SupplierCreatePage
        },        
        {
            path: '/suppliers/:id',
            name: 'accounting.suppliers.show',
            component: SupplierViewPage,
            props: true
        },
        {
            path: '/suppliers/:id/edit',
            name: 'accounting.suppliers.edit',
            component: SupplierEditPage,
            props: true
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
