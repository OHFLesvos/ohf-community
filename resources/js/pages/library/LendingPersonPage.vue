<template>
    <div v-if="person">

        <!-- Heading -->
        <h2 class="mb-3">
            {{ person.full_name }}
            <small class="d-block d-sm-inline">
                {{ person.nationality }}<template v-if="person.nationality && person.date_of_birth">,</template>
                {{ moment(person.date_of_birth).format("LL") }}
            </small>
        </h2>

        <b-tabs content-class="mt-3">

            <!-- Lendings -->
            <b-tab :title="$t('library.lendings')" active>
                <person-lendings-table
                    v-if="lendings"
                    :lendings="lendings"
                    :disabled="busy"
                    @return="returnBook"
                    @extend="extendLending"
                />

                <!-- Lend modal button -->
                <p v-if="canLend">
                    <b-button
                        variant="primary"
                        :disabled="busy"
                        v-b-modal.lendBookModal
                    >
                        <font-awesome-icon icon="plus-circle" />
                        {{ $t('library.lend_a_book') }}
                    </b-button>
                </p>

                <!-- Lend book modal -->
                <b-modal
                    id="lendBookModal"
                    :title="$t('library.lend_a_book')"
                    @ok="lendBookToPerson"
                    @hidden="selectedBookId = null"
                >
                    <library-book-autocomplete-input
                        available-only
                        @select="selectedBookId = $event"
                    />
                    <template v-slot:modal-footer="{ ok }">

                        <!-- Lend book button -->
                        <b-button
                            variant="primary"
                            :disabled="!selectedBookId || busy"
                            @click="ok()"
                        >
                            <font-awesome-icon icon="check" />
                            {{ $t('library.lend_book') }}
                        </b-button>

                        <!-- Register book button -->
                        <b-button
                            v-if="canRegisterBook"
                            variant="secondary"
                            :disabled="busy"
                            v-b-modal.registerBookModal
                        >
                            <font-awesome-icon icon="plus-circle" />
                            {{ $t('library.new_book') }}
                        </b-button>

                    </template>
                </b-modal>

                <!-- Register book modal -->
                <b-modal
                    id="registerBookModal"
                    :title="$t('library.register_new_book')"
                    ok-only
                    :ok-disabled="busy"
                    body-class="pb-0"
                    @shown="$refs.registerBookForm.focus()"
                    @ok="handleOkRegisterBook"
                >
                    <book-form
                        ref="registerBookForm"
                        compact
                        no-buttons
                        :disabled="busy"
                        @submit="registerAndLendBookToPerson"
                    />
                    <template v-slot:modal-ok>
                        <font-awesome-icon icon="check" />
                        {{ $t('library.register_and_lend_book') }}
                    </template>
                </b-modal>

            </b-tab>

            <!-- Logs -->
            <b-tab :title="$t('app.log')" lazy>
                <person-log-table
                    :person-id="personId"
                />
            </b-tab>
        </b-tabs>

    </div>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import moment from 'moment'
import { findLendingsOfPerson, lendBookToPerson, extendLendingToPerson, returnBookFromPerson } from '@/api/library'
import { findPerson } from '@/api/people'
import { handleAjaxError, showSnackbar } from '@/utils'
import LibraryBookAutocompleteInput from '@/components/library/input/LibraryBookAutocompleteInput'
import BookForm from '@/components/library/forms/BookForm'
import PersonLendingsTable from '@/components/library/PersonLendingsTable'
import PersonLogTable from '@/components/library/PersonLogTable'
export default {
    components: {
        LibraryBookAutocompleteInput,
        BookForm,
        PersonLendingsTable,
        PersonLogTable
    },
    props: {
        personId: {
            required: true
        }
    },
    data () {
        return {
            person: null,
            lendings: null,
            canLend: false,
            canRegisterBook: false,
            defaultExtendDuration: 0,
            selectedBookId: null,
            busy: false
        }
    },
    created () {
        this.loadPerson()
        this.loadLendings()
    },
    methods: {
        moment,
        loadPerson () {
            findPerson(this.personId)
                .then(data => {
                    this.person = data.data
                })
                .catch(err => console.error(err))
        },
        loadLendings () {
            this.busy = true
            findLendingsOfPerson(this.personId)
                .then(data => {
                    this.lendings = data.data
                    this.canLend = data.meta.can_lend
                    this.canRegisterBook = data.meta.can_register_book
                    this.defaultExtendDuration = data.meta.default_extend_duration
                })
                .catch(err => console.error(err))
                .finally(() => this.busy = false)
        },
        lendBookToPerson (bvModalEvt) {
            bvModalEvt.preventDefault()
            if (this.selectedBookId) {
                this.busy = true
                lendBookToPerson(this.selectedBookId, this.personId)
                    .then((data) => {
                        showSnackbar(data.message)
                        this.loadLendings()
                        this.$nextTick(() => {
                            this.$bvModal.hide('lendBookModal')
                        })
                    })
                    .catch(handleAjaxError)
                    .finally(() => this.busy = false)
            }
        },
        handleOkRegisterBook (evt) {
            evt.preventDefault()
            this.$refs.registerBookForm.submit()
        },
        registerAndLendBookToPerson (newBook) {
            this.busy = true
            lendBookToPerson(newBook, this.personId)
                .then((data) => {
                    showSnackbar(data.message)
                    this.loadLendings()
                    this.$nextTick(() => {
                        this.$bvModal.hide('registerBookModal')
                        this.$bvModal.hide('lendBookModal')
                    })
                })
                .catch(handleAjaxError)
                .finally(() => this.busy = false)
        },
        extendLending (book_id) {
            var days = prompt(`${this.$t('app.number_of_days')}:`, this.defaultExtendDuration)
            if (days != null && days > 0) {
                this.busy = true
                extendLendingToPerson(book_id, this.personId, days)
                    .then((data) => {
                        showSnackbar(data.message)
                        this.loadLendings()
                    })
                    .catch(handleAjaxError)
                    .finally(() => this.busy = false)
            }
        },
        returnBook (book_id) {
            this.busy = true
            returnBookFromPerson(book_id, this.personId)
                .then((data) => {
                    showSnackbar(data.message)
                    this.loadLendings()
                })
                .catch(handleAjaxError)
                .finally(() => this.busy = false)
        }
    }
}
</script>
