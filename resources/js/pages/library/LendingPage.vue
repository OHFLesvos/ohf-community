<template>
    <b-tabs content-class="mt-3">
        <b-tab
            :title="$t('app.search')"
            active
        >
            <div class="row">

                <div class="col-md">
                    <b-card
                        :header="ucFirst($t('people.persons'))"
                        class="mb-4"
                        body-class="pb-4"
                    >
                        <div class="form-row">
                            <div class="col">
                                <person-autocomplete-input @select="navigateToPerson" />
                            </div>
                            <div class="col-auto">
                                <b-button
                                    variant="outline-secondary"
                                    @click="registerPerson()"
                                >
                                    <font-awesome-icon icon="plus-circle" />
                                    <span class="d-none d-sm-inline">{{ $t('app.register') }}</span>
                                </b-button>
                            </div>
                        </div>
                    </b-card>
                </div>

                <div class="col-md">
                    <b-card
                        :header="$t('library.books')"
                        class="mb-4"
                        body-class="pb-4"
                    >
                        <library-book-autocomplete-input @select="navigateToBook" />
                    </b-card>
                </div>

            </div>
            <person-register-modal
                ref="registerPersonModal"
            />

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
import { ucFirst } from '@/utils'
import libraryApi from '@/api/library'
import PersonAutocompleteInput from '@/components/people/PersonAutocompleteInput'
import LibraryBookAutocompleteInput from '@/components/library/input/LibraryBookAutocompleteInput'
import PersonRegisterModal from '@/components/people/PersonRegisterModal'
import BorrowersTable from '@/components/library/BorrowersTable'
import LentBooksTable from '@/components/library/LentBooksTable'
import { BCard, BTabs, BTab, BBadge } from 'bootstrap-vue'
export default {
    components: {
        PersonAutocompleteInput,
        LibraryBookAutocompleteInput,
        PersonRegisterModal,
        BorrowersTable,
        LentBooksTable,
        BCard,
        BTabs,
        BTab,
        BBadge
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
    },
    methods: {
        ucFirst,
        navigateToPerson (val) {
            if (val) {
                document.location = this.route('library.lending.person', [val])
            }
        },
        navigateToBook (val) {
            if (val) {
                document.location = this.route('library.lending.book', [val])
            }
        },
        registerPerson () {
            this.$refs.registerPersonModal.open()
        }
    }
}
</script>
