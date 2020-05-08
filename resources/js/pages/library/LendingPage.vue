<template>
    <b-tabs content-class="mt-3">
        <b-tab
            :title="$t('app.search')"
            active
        >
            <div class="row">
                <div class="col-md">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between">
                            {{ ucFirst($t('people.persons')) }}
                        </div>
                        <div class="card-body pb-4">
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
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            {{ $t('library.books') }}
                        </div>
                        <div class="card-body pb-4">
                            <library-book-autocomplete-input @select="navigateToBook" />
                        </div>
                    </div>
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
import { fetchLendingsStatistics } from '@/api/library'
import PersonAutocompleteInput from '@/components/people/PersonAutocompleteInput'
import LibraryBookAutocompleteInput from '@/components/library/input/LibraryBookAutocompleteInput'
import PersonRegisterModal from '@/components/people/PersonRegisterModal'
import BorrowersTable from '@/components/library/BorrowersTable'
import LentBooksTable from '@/components/library/LentBooksTable'
export default {
    components: {
        PersonAutocompleteInput,
        LibraryBookAutocompleteInput,
        PersonRegisterModal,
        BorrowersTable,
        LentBooksTable
    },
    data () {
        return {
            numBorrowers: null,
            numLentBooks: null,
        }
    },
    created () {
        fetchLendingsStatistics()
            .then((data) => {
                this.numBorrowers = data.num_borrowers
                this.numLentBooks = data.num_lent_books
            })
            .catch(err => console.error(err))
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
