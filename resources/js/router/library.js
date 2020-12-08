import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import i18n from '@/plugins/i18n'
import ziggyRoute from '@/plugins/ziggy'

import PageHeader from '@/components/ui/PageHeader'
import NotFoundPage from '@/pages/NotFoundPage'

import { can } from '@/plugins/laravel'

import LendingPage from '@/pages/library/LendingPage'
import LendigSearch from '@/components/library/LendigSearch'
import BorrowersTable from '@/components/library/BorrowersTable'
import LentBooksTable from '@/components/library/LentBooksTable'
import LendingPersonPage from '@/pages/library/LendingPersonPage'
import BooksPage from '@/pages/library/BooksPage'
import BookRegisterPage from '@/pages/library/BookRegisterPage'
import BookEditPage from '@/pages/library/BookEditPage'
import LendingBookPage from '@/pages/library/LendingBookPage'
import ExportPage from '@/pages/library/ExportPage'

const router = new VueRouter({
    mode: 'history',
    base: '/library/',
})
router.addRoutes([
    {
        path: '/',
        redirect: { name: 'library.lending.index' }
    },
    {
        // Lending overview
        path: '/lending',
        components: {
            default: LendingPage,
            header: PageHeader
        },
        props: {
            header:  {
                title: i18n.t('app.overview'),
                buttons: [
                    {
                        to: { name: 'library.books.create' },
                        variant: 'primary',
                        icon: 'plus-circle',
                        text: i18n.t('library.register_new_book'),
                        show: can('create-books')
                    },
                    {
                        to: { name: 'library.export' },
                        icon: 'download',
                        text: i18n.t('app.export'),
                        show: can('operate-library')
                    }
                ]
            }
        },
        children: [
            {
                path: '',
                name: 'library.lending.index',
                component: LendigSearch
            },
            {
                path: 'borrowers',
                name: 'library.lending.borrowers',
                component: BorrowersTable
            },
            {
                path: 'lent_books',
                name: 'library.lending.lent_books',
                component: LentBooksTable
            },
            {
                path: '/books',
                name: 'library.books.index',
                component: BooksPage,
            }
        ]
    },
    {
        // Lending person (borrower)
        path: '/lending/person/:personId',
        name: 'library.lending.person',
        components: {
            default: LendingPersonPage,
            header: PageHeader
        },
        props: {
            default: true,
            header: (route) => ({
                title: i18n.t('people.person'),
                buttons: [
                    {
                        href: ziggyRoute('people.show', route.params.personId),
                        icon: 'users',
                        text: i18n.t('people.view_person'),
                        // TODO 'authorized' => Auth::user()->can('view', $person),
                    },
                    {
                        to: { name: 'library.lending.index' },
                        icon: 'times-circle',
                        text: i18n.t('app.close'),
                        show: can('operate-library')
                    }
                ]
            })
        }
    },
    {
        // Lending book
        path: '/lending/book/:bookId(\\d+)',
        name: 'library.lending.book',
        components: {
            default: LendingBookPage,
            header: PageHeader
        },
        props: {
            default: true,
            header: (route) => ({
                title: i18n.t('library.book'),
                buttons: [
                    {
                        to: { name: 'library.books.edit', params: { bookId: route.params.bookId } },
                        icon: 'edit',
                        text: i18n.t('app.edit'),
                        variant: 'primary',
                        show: can('operate-library')
                        // TODO 'authorized' => Auth::user()->can('update', $book),
                    },
                    {
                        to: { name: 'library.books.index' },
                        icon: 'times-circle',
                        text: i18n.t('app.close'),
                        show: can('operate-library')
                    }
                ]
            })
        }
    },
    {
        // Create book
        path: '/books/create',
        name: 'library.books.create',
        components: {
            default: BookRegisterPage,
            header: PageHeader,
        },
        props: {
            header: {
                title: i18n.t('library.register_book'),
                buttons: [
                    {
                        click: () => router.back(),
                        icon: 'times-circle',
                        text: i18n.t('app.cancel'),
                    }
                ]
            }
        }
    },
    {
        // Edit book
        path: '/books/:bookId(\\d+)/edit',
        name: 'library.books.edit',
        components: {
            default: BookEditPage,
            header: PageHeader,
        },
        props: {
            default: true,
            header: {
                title: i18n.t('library.edit_book'),
                buttons: [
                    {
                        click: () => router.back(),
                        icon: 'times-circle',
                        text: i18n.t('app.cancel'),
                    }
                ]
            }
        }
    },
    {
        // Export
        path: '/export',
        name: 'library.export',
        components: {
            default: ExportPage,
            header: PageHeader,
        },
        props: {
            header: {
                title: i18n.t('app.export'),
                buttons: [
                    {
                        click: () => router.back(),
                        icon: 'times-circle',
                        text: i18n.t('app.close'),
                    }
                ]
            }
        }
    },
    {
        // Catch-all 404
        path: '*',
        component: NotFoundPage
    }
])
export default router
