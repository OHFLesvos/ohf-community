<template>
    <b-tabs content-class="mt-3">
        <b-tab
            :title="$t('app.search')"
            active
        >
            <lendig-search />
        </b-tab>

        <b-tab lazy>
            <template v-slot:title>
                {{ $t('library.borrowers') }}
                <b-badge
                    v-if="numBorrowers !== null"
                    variant="primary"
                    class="d-none d-sm-inline"
                >
                    {{ numBorrowers }}
                </b-badge>
            </template>
            <borrowers-table />
        </b-tab>

        <b-tab lazy>
            <template v-slot:title>
                {{ $t('library.lent_books') }}
                <b-badge
                    v-if="numLentBooks !== null"
                    variant="primary"
                    class="d-none d-sm-inline"
                >
                    {{ numLentBooks }}
                </b-badge>
            </template>
            <lent-books-table />
        </b-tab>
    </b-tabs>
</template>

<script>
import libraryApi from '@/api/library'
import LendigSearch from '@/components/library/LendigSearch'
import BorrowersTable from '@/components/library/BorrowersTable'
import LentBooksTable from '@/components/library/LentBooksTable'
export default {
    components: {
        LendigSearch,
        BorrowersTable,
        LentBooksTable
    },
    data () {
        return {
            numBorrowers: null,
            numLentBooks: null,
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
