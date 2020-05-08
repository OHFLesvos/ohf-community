<template>
    <div v-if="book">

        <!-- Heading -->
        <h2 class="mb-3">
            {{ book.title }}
            <small class="d-block d-sm-inline">
                {{ book.author }}
            </small>
        </h2>

        <!-- Info -->
        <p v-if="book.isbn">
            <strong>{{ $t('library.isbn') }}:</strong> {{ book.isbn }}
        </p>
        <p v-if="book.language">
            <strong>{{ $t('app.language') }}:</strong> {{ book.language }}
        </p>

        <!-- Lending -->
        <template v-if="lending">
            <b-alert
                v-if="isOverdue"
                variant="danger"
                show
            >
                {{ $t('library.book_is_overdue') }}
            </b-alert>
            <b-alert
                v-else-if="isSoonOverdue"
                variant="warning"
                show
            >
                {{ $t('library.book_is_overdue_soon') }}
            </b-alert>

            <!-- Person info -->
            <b-alert
                variant="info"
                show
                v-html="lentToPersonMessage"
            >
            </b-alert>

            <p>
                <!-- Return book -->
                <b-button
                    variant="success"
                    :disabled="busy"
                    @click="returnBook"
                >
                    <font-awesome-icon icon="inbox" />
                    {{ $t('library.return') }}
                </b-button>

                <!-- Extend lending -->
                <b-button
                    variant="primary"
                    :disabled="busy"
                    @click="extendLending"
                >
                    <font-awesome-icon icon="calendar-plus" />
                    {{ $t('library.extend') }}
                </b-button>
            </p>
        </template>
        <template v-else>
            <b-alert variant="success">
                {{ $t('library.book_is_available') }}
            </b-alert>

            <!-- Lend modal button -->
            <p>
                <b-button
                    variant="primary"
                    :disabled="busy"
                    v-b-modal.lendBookModal
                >
                    <font-awesome-icon icon="plus-circle" />
                    {{ $t('library.lend_book') }}
                </b-button>
            </p>

            <!-- Lend book modal -->
            <b-modal
                id="lendBookModal"
                :title="$t('library.lend_book')"
                @ok="lendBook"
                @hidden="selectedPersonId = null"
            >
                <person-autocomplete-input
                    @select="selectedPersonId = $event"
                />
                <template v-slot:modal-footer="{ ok }">

                    <!-- Lend book button -->
                    <b-button
                        variant="primary"
                        :disabled="!selectedPersonId || busy"
                        @click="ok()"
                    >
                        <font-awesome-icon icon="check" />
                        {{ $t('library.lend_book') }}
                    </b-button>

                </template>
            </b-modal>

        </template>
    </div>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import moment from 'moment'
import { handleAjaxError, showSnackbar } from '@/utils'
import { findBook, findLendingOfBook, lendBook, extendLending, returnBook } from '@/api/library'
import PersonAutocompleteInput from '@/components/people/PersonAutocompleteInput'
export default {
    components: {
        PersonAutocompleteInput
    },
    props: {
        bookId: {
            required: true
        }
    },
    data () {
        return {
            book: null,
            lending: null,
            defaultExtendDuration: 0,
            busy: false,
            selectedPersonId: null
        }
    },
    computed: {
        isOverdue () {
            return moment(this.lending.return_date).isBefore(moment(), 'day')
        },
        isSoonOverdue () {
            return moment(this.lending.return_date).isSame(moment(), 'day')
        },
        lentToPersonMessage () {
            if (this.lending.person) {
                return this.$t('library.book_is_lent_to_person_until', {
                    route: this.route('library.lending.person', [this.lending.person.public_id]),
                    person: this.lending.person.full_name,
                    until: moment(this.lending.return_date).format("LL")
                })
            }
            // @php
            //     $thrashedPerson = $lending->person()->withTrashed()->first();
            // @endphp
            // @isset($thrashedPerson)
            //     @lang('library.book_is_lent_to_soft_deleted_person_until', [ 'person' => $thrashedPerson->fullName, 'until' => $lending->return_date->toDateString() ])
            // @else -->
            return this.$t('library.book_is_lent_to_deleted_person_until', {
                until: moment(this.lending.return_date).format("LL")
            })
        }
    },
    created () {
        this.loadBook()
        this.loadLending()
    },
    methods: {
        moment,
        loadBook () {
            findBook(this.bookId)
                .then(data => {
                    this.book = data.data
                })
                .catch(err => console.error(err))
        },
        loadLending () {
            findLendingOfBook(this.bookId)
                .then(data => {
                    if (data.data) {
                        this.lending = data.data
                        this.defaultExtendDuration = data.meta.default_extend_duration
                    } else {
                        this.lending = null
                    }
                })
                .catch(err => console.error(err))
        },
        extendLending () {
            var days = prompt(`${this.$t('app.number_of_days')}:`, this.defaultExtendDuration)
            if (days != null && days > 0) {
                this.busy = true
                extendLending(this.bookId, days)
                    .then((data) => {
                        showSnackbar(data.message)
                        this.loadLending()
                    })
                    .catch(handleAjaxError)
                    .finally(() => this.busy = false)
            }
        },
        returnBook () {
            this.busy = true
            returnBook(this.bookId)
                .then((data) => {
                    showSnackbar(data.message)
                    this.loadLending()
                })
                .catch(handleAjaxError)
                .finally(() => this.busy = false)
        },
        lendBook (bvModalEvt) {
            bvModalEvt.preventDefault()
            if (this.selectedPersonId) {
                this.busy = true
                lendBook(this.bookId, this.selectedPersonId)
                    .then((data) => {
                        showSnackbar(data.message)
                        this.loadLending()
                        this.$nextTick(() => {
                            this.$bvModal.hide('lendBookModal')
                        })
                    })
                    .catch(handleAjaxError)
                    .finally(() => this.busy = false)
            }
        },
    }
}
</script>
