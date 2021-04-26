import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import i18n from '@/plugins/i18n'
import ziggyRoute from '@/plugins/ziggy'

import { rememberRoute, previouslyRememberedRoute } from '@/utils/router'

import PageHeader from '@/components/ui/PageHeader'
import TabNav from '@/components/ui/TabNav'

import DonorsIndexPage from '@/pages/fundraising/DonorsIndexPage'
import DonorCreatePage from '@/pages/fundraising/DonorCreatePage'
import DonorShowPage from '@/pages/fundraising/DonorShowPage'
import DonorDetails from '@/components/fundraising/DonorDetails'
import DonorDonations from '@/components/fundraising/DonorDonations'
import DonorComments from '@/components/fundraising/DonorComments'
import DonorEditPage from '@/pages/fundraising/DonorEditPage'
import DonationsIndexPage from '@/pages/fundraising/DonationsIndexPage'
import DonationEditPage from '@/pages/fundraising/DonationEditPage'
import DonationsImportPage from '@/pages/fundraising/DonationsImportPage'
import NotFoundPage from '@/pages/NotFoundPage'

import { can } from '@/plugins/laravel'

const overviewNavItems = [
    {
        to: { name: 'fundraising.donors.index' },
        icon: 'users',
        text: i18n.t('Donors'),
        show: can('view-fundraising-entities')
    },
    {
        to: { name: 'fundraising.donations.index' },
        icon: 'donate',
        text: i18n.t('Donations'),
        show: can('view-fundraising-entities')
    }
]

export default new VueRouter({
    mode: 'history',
    base: '/fundraising/',
    routes: [
        {
            path: '/',
            redirect: { name: 'fundraising.donors.index' }
        },
        {
            // Display a listing of the donors.
            path: '/donors',
            name: 'fundraising.donors.index',
            components: {
                default: DonorsIndexPage,
                header: PageHeader,
                beforeContent: TabNav
            },
            props: {
                default: (route) => ({ tag: route.query.tag }),
                header:  {
                    title: i18n.t('Overview'),
                    buttons: [
                        {
                            to: { name: 'fundraising.donors.create' },
                            variant: 'primary',
                            icon: 'plus-circle',
                            text: i18n.t('Add'),
                            show: can('manage-fundraising-entities')
                        },
                        {
                            href: ziggyRoute('api.fundraising.donors.export'),
                            icon: 'download',
                            text: i18n.t('Export'),
                            show: can('view-fundraising-entities')
                        }
                    ]
                },
                beforeContent: {
                    items: overviewNavItems,
                    exact: false
                }
            }
        },
        {
            // Show the form for creating a new donor.
            path: '/donors/create',
            name: 'fundraising.donors.create',
            components: {
                default: DonorCreatePage,
                header: PageHeader
            },
            props: {
                header: {
                    title: i18n.t('Add donor'),
                    buttons: [
                        {
                            to: { name: 'fundraising.donors.index' },
                            icon: 'times-circle',
                            text: i18n.t('Cancel'),
                            show: can('view-fundraising-entities')
                        }
                    ]
                }
            }
        },
        {
            // Display the specified donor.
            path: '/donors/:id(\\d+)',
            components: {
                default: DonorShowPage,
                header: PageHeader
            },
            props: {
                default: true,
                header: (route) => ({
                    title: i18n.t('Donor'),
                    buttons: [
                        {
                            to: { name: 'fundraising.donors.edit', params: { id: route.params.id } },
                            variant: 'primary',
                            icon: 'pencil-alt',
                            text: i18n.t('Edit'),
                            show: can('manage-fundraising-entities')
                        },
                        {
                            href: ziggyRoute('api.fundraising.donors.donations.export', route.params.id),
                            icon: 'download',
                            text: i18n.t('Export'),
                            show: can('view-fundraising-entities')
                        },
                        {
                            href: ziggyRoute('api.fundraising.donors.vcard', route.params.id),
                            icon: 'address-card',
                            text: i18n.t('vCard'),
                            show: can('view-fundraising-entities')
                        },
                        {
                            to: previouslyRememberedRoute('fundraising.donors.show', { name: 'fundraising.donors.index' }),
                            icon: 'times-circle',
                            text: i18n.t('Close'),
                            show: can('view-fundraising-entities')
                        }
                    ]
                })
            },
            children: [
                {
                    path: '',
                    name: 'fundraising.donors.show',
                    component: DonorDetails,
                    props: true
                },
                {
                    path: 'donations',
                    name: 'fundraising.donors.show.donations',
                    component: DonorDonations,
                    props: true
                },
                {
                    path: 'comments',
                    name: 'fundraising.donors.show.comments',
                    component: DonorComments,
                    props: true
                },
            ],
            beforeEnter: (to, from, next) => {
                rememberRoute(to, from, [
                    'fundraising.donors.create',
                    'fundraising.donors.edit',
                ])
                next()
            }
        },
        {
            // Show the form for editing the donor.
            path: '/donors/:id(\\d+)/edit',
            name: 'fundraising.donors.edit',
            components: {
                default: DonorEditPage,
                header: PageHeader
            },
            props: {
                default: true,
                header: (route)  => ({
                    title: i18n.t('Edit donor'),
                    buttons: [
                        {
                            to: { name: 'fundraising.donors.show', params: { id: route.params.id } },
                            icon: 'times-circle',
                            text: i18n.t('Cancel'),
                            show: can('view-fundraising-entities')
                        }
                    ]
                })
            }
        },
        {
            // Display a listing of the donations.
            path: '/donations',
            name: 'fundraising.donations.index',
            components: {
                default: DonationsIndexPage,
                header: PageHeader,
                beforeContent: TabNav
            },
            props: {
                default: true,
                header: {
                    title: i18n.t('Overview'),
                    buttons: [
                        {
                            href: ziggyRoute('api.fundraising.donations.export'),
                            icon: 'download',
                            text: i18n.t('Export'),
                            show: can('view-fundraising-entities')
                        },
                        {
                            to: { name: 'fundraising.donations.import' },
                            icon: 'upload',
                            text: i18n.t('Import'),
                            show: can('manage-fundraising-entities')
                        },
                    ]
                },
                beforeContent: {
                    items: overviewNavItems
                }
            }
        },
        {
            // Show the form for editing the donation.
            path: '/donations/:id(\\d+)/edit',
            name: 'fundraising.donations.edit',
            components: {
                default: DonationEditPage,
                header: PageHeader
            },
            props: {
                default: true,
                header: () => ({
                    title: i18n.t('Edit donation'),
                    buttons: [
                        {
                            to: previouslyRememberedRoute('fundraising.donors.show', { name: 'fundraising.donations.index' } ),
                            icon: 'times-circle',
                            text: i18n.t('Cancel'),
                            show: can('view-fundraising-entities')
                        }
                    ]
                })
            },
            beforeEnter: (to, from, next) => {
                rememberRoute(to, from)
                next()
            }
        },
        {
            // Show the form for importing donations
            path: '/donations/import',
            name: 'fundraising.donations.import',
            components: {
                default: DonationsImportPage,
                header: PageHeader
            },
            props: {
                header: {
                    title: i18n.t('Import'),
                    buttons: [
                        {
                            to: { name: 'fundraising.donations.index' },
                            icon: 'times-circle',
                            text: i18n.t('Cancel'),
                            show: can('view-fundraising-entities')
                        }
                    ]
                }
            }
        },
        {
            path: '*',
            component: NotFoundPage
        }
    ]
})
