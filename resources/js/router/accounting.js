import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import SuppliersPage from '@/pages/accounting/SuppliersPage'
import SupplierEditPage from '@/pages/accounting/SupplierEditPage'
import SupplierCreatePage from '@/pages/accounting/SupplierCreatePage'
import NotFoundPage from '@/pages/NotFoundPage'

export default new VueRouter({
    mode: 'history',
    base: '/accounting/',
    routes: [
        {
            path: '/suppliers',
            name: 'accounting.suppliers.index',
            component: SuppliersPage
        },
        {
            path: '/suppliers/create',
            name: 'accounting.suppliers.create',
            component: SupplierCreatePage
        },        
        {
            path: '/suppliers/:id',
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
