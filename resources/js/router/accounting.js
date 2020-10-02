import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import PageHeader from '@/components/ui/PageHeader'
import SuppliersIndexPage from '@/pages/accounting/SuppliersIndexPage'
import SupplierCreatePage from '@/pages/accounting/SupplierCreatePage'
import SupplierViewPage from '@/pages/accounting/SupplierViewPage'
import SupplierEditPage from '@/pages/accounting/SupplierEditPage'
import NotFoundPage from '@/pages/NotFoundPage'

import i18n from '@/plugins/i18n'

export default new VueRouter({
    mode: 'history',
    base: '/accounting/',
    routes: [
        {
            path: '/suppliers',
            name: 'accounting.suppliers.index',
            components: {
                default: SuppliersIndexPage,
                header: PageHeader
            },
            props: {
                header:  {
                    title: i18n.t('app.overview'),
                    buttons: [
                        {
                            to: { name: 'accounting.suppliers.create' },
                            variant: 'primary',
                            icon: 'plus-circle',
                            text: i18n.t('app.add'),
                            show: true // can('manage-fundraising-entities')
                        },
                    ]
                }
            }
        },
        {
            path: '/suppliers/create',
            name: 'accounting.suppliers.create',
            components: {
                default: SupplierCreatePage,
                header: PageHeader
            },
            props: {
                header:  {
                    title: i18n.t('accounting.register_supplier')
                }
            }
        },
        {
            path: '/suppliers/:id',
            name: 'accounting.suppliers.show',
            components: {
                default: SupplierViewPage,
                header: PageHeader
            },
            props: {
                default: true,
                header: (route) => ({
                    title: i18n.t('accounting.supplier'),
                    buttons: [
                        {
                            to: { name: 'accounting.suppliers.index' },
                            variant: 'secondary',
                            icon: 'arrow-left',
                            text: i18n.t('app.overview'),
                            show: true
                        },
                        {
                            to: { name: 'accounting.suppliers.edit', params: { id: route.params.id } },
                            variant: 'primary',
                            icon: 'edit',
                            text: i18n.t('app.edit'),
                            show: true // can('manage-fundraising-entities') // supplier.can_update
                        },
                    ]
                })
            }            
        },
        {
            path: '/suppliers/:id/edit',
            name: 'accounting.suppliers.edit',
            components: {
                default: SupplierEditPage,
                header: PageHeader
            },
            props: {
                default: true,
                header: {
                    title: i18n.t('accounting.edit_supplier'),
                }
            } 
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
