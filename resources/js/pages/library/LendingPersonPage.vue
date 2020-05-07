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

        <!-- Lendings -->
        <template v-if="lendings">
            <div
                v-if="lendings.length > 0"
                class="table-responsive"
            >
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ $t('library.book') }}</th>
                            <th class="d-none d-sm-table-cell">{{ $t('library.lent') }}</th>
                            <th>{{ $t('library.return') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="lending in lendings"
                            :key="lending.id"
                            :class="{
                                'table-danger': isOverdue(lending),
                                'table-warning': isSoonOverdue(lending)
                            }">
                            <td class="align-middle">
                                <a :href="route('library.lending.book', [lending.book.id])">
                                    {{ lending.book.title }}
                                    <template v-if="lending.book.author">
                                        ({{ lending.book.author }})
                                    </template>
                                </a>
                            </td>
                            <td class="align-middle d-none d-sm-table-cell">
                                {{ moment(lending.lending_date).format("LL") }}
                            </td>
                            <td class="align-middle">
                                {{ moment(lending.return_date).format("LL") }}
                            </td>
                            <td class="fit align-middle">

                                <!-- Return book -->
                                <b-button
                                    variant="success"
                                    size="sm"
                                    @click="returnBook(lending.book.id)"
                                >
                                    <font-awesome-icon icon="inbox" />
                                    <span class="d-none d-sm-inline"> {{ $t('library.return') }}</span>
                                </b-button>

                                <!-- Extend lending  -->
                                <b-button
                                    variant="primary"
                                    size="sm"
                                    @click="extendLending(lending.book.id)"
                                >
                                    <font-awesome-icon icon="calendar-plus" />
                                    <span class="d-none d-sm-inline"> {{ $t('library.extend') }}</span>
                                </b-button>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <b-alert
                v-else
                show
            >
                {{ $t('library.no_books_lent') }}
            </b-alert>
        </template>

        <!-- Lend button -->
        <p v-if="canLend">
            <b-button
                variant="primary"
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
        >
            <library-book-autocomplete-input
                available-only
                @select="selectExistingBook"
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
            body-class="pb-0"
            @shown="$refs.registerBookForm.focus()"
            @ok="handleOkRegisterBook"
        >
            <register-book-form
                ref="registerBookForm"
                compact
                no-buttons
                @submit="registerAndLendBookToPerson"
            />
            <template v-slot:modal-ok>
                <font-awesome-icon icon="check" />
                {{ $t('library.register_and_lend_book') }}
            </template>
        </b-modal>

    </div>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import moment from 'moment'
import axios from '@/plugins/axios'
import { handleAjaxError, showSnackbar } from '@/utils'
import LibraryBookAutocompleteInput from '@/components/library/input/LibraryBookAutocompleteInput'
import RegisterBookForm from '@/components/library/forms/RegisterBookForm'
export default {
    components: {
        LibraryBookAutocompleteInput,
        RegisterBookForm
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
            axios.get(this.route('api.people.show', [this.personId]))
                .then(res => {
                    this.person = res.data.data
                })
                .catch(err => console.error(err))
        },
        loadLendings () {
            axios.get(this.route('api.library.lending.person', [this.personId]))
                .then(res => {
                    this.lendings = res.data.data
                    this.canLend = res.data.meta.can_lend
                    this.canRegisterBook = res.data.meta.can_register_book
                    this.defaultExtendDuration = res.data.meta.default_extend_duration
                })
                .catch(err => console.error(err))
        },
        isOverdue (lending) {
            return moment(lending.return_date).isBefore(moment(), 'day')
        },
        isSoonOverdue (lending) {
            return moment(lending.return_date).isSame(moment(), 'day')
        },
        selectExistingBook (bookId) {
            this.selectedBookId = bookId
        },
        lendBookToPerson (bvModalEvt) {
            bvModalEvt.preventDefault()
            if (this.selectedBookId) {
                this.busy = true
                axios.post(this.route('api.library.lending.lendBookToPerson', [this.personId]), {
                        book_id: this.selectedBookId
                    })
                    .then((res) => {
                        showSnackbar(res.data.message)
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
            this.$refs.registerBookForm.handleSubmit()
        },
        registerAndLendBookToPerson (newBook) {
            this.busy = true
            axios.post(this.route('api.library.lending.lendBookToPerson', [this.personId]), {
                    ...newBook
                })
                .then((res) => {
                    showSnackbar(res.data.message)
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
                axios.post(this.route('api.library.lending.extendBookToPerson', [this.personId]), {
                        book_id: book_id,
                        days: days
                    })
                    .then((res) => {
                        showSnackbar(res.data.message)
                        this.loadLendings()
                    })
            }
        },
        returnBook (book_id) {
            axios.post(this.route('api.library.lending.returnBookFromPerson', [this.personId]), {
                    book_id: book_id,
                })
                .then((res) => {
                    showSnackbar(res.data.message)
                    this.loadLendings()
                })
        }
    }
}
</script>
