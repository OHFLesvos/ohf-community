<template>
    <div>
        <tab-nav :items="tabNavItems">
            <template v-slot:after(borrowers)>
                <b-badge
                    v-if="numBorrowers > 0"
                    class="ml-1"
                >
                    {{ numBorrowers }}
                </b-badge>
            </template>
            <template v-slot:after(lent_books)>
                <b-badge
                    v-if="numLentBooks > 0"
                    class="ml-1"
                >
                    {{ numLentBooks }}
                </b-badge>
            </template>
        </tab-nav>
        <router-view />
    </div>
</template>

<script>
import { can } from '@/plugins/laravel'
import libraryApi from '@/api/library'
import TabNav from '@/components/ui/TabNav'
export default {
    components: {
        TabNav
    },
    data () {
        return {
            numBorrowers: null,
            numLentBooks: null,
            tabNavItems: [
                {
                    to: { name: 'library.lending.index' },
                    icon: 'search',
                    text: this.$t('library.lending'),
                    show: can('operate-library')
                },
                {
                    to: { name: 'library.lending.borrowers' },
                    icon: 'users',
                    text: this.$t('library.borrowers'),
                    show: can('operate-library'),
                    key: 'borrowers'
                },
                {
                    to: { name: 'library.lending.lent_books' },
                    icon: 'inbox',
                    text: this.$t('library.lent_books'),
                    show: can('operate-library'),
                    key: 'lent_books'
                },
                {
                    to: { name: 'library.books.index' },
                    icon: 'book',
                    text: this.$t('library.books'),
                    show: can('operate-library')
                }
            ]
        }
    },
    async created () {
        try {
            let data = await libraryApi.fetchLendingsStatistics()
            this.numBorrowers = data.num_borrowers
            this.numLentBooks = data.num_lent_books
        } catch (err) {
            // Noop
        }
    }
}
</script>
