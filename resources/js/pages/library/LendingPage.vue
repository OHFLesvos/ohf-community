<template>
    <div>
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        {{ ucFirst($t('people.persons')) }}
                        <a
                            v-if="numBorrowers !== null"
                            :href="route('library.lending.persons')"
                        >
                            {{ $t('library.borrowers') }} ({{ numBorrowers }})
                        </a>
                        <span v-else>...</span>
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
                        <a
                            v-if="numLentBooks !== null"
                            :href="route('library.lending.books')"
                        >
                            {{ $t('library.lent_books') }} ({{ numLentBooks }})
                        </a>
                        <span v-else>...</span>
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
    </div>
</template>

<script>
import { ucFirst } from '@/utils'
import axios from '@/plugins/axios'
import PersonAutocompleteInput from '@/components/people/PersonAutocompleteInput'
import LibraryBookAutocompleteInput from '@/components/library/LibraryBookAutocompleteInput'
import PersonRegisterModal from '@/components/people/PersonRegisterModal'
export default {
    components: {
        PersonAutocompleteInput,
        LibraryBookAutocompleteInput,
        PersonRegisterModal
    },
    data () {
        return {
            numBorrowers: null,
            numLentBooks: null,
        }
    },
    created () {
        axios.get(this.route('api.library.lending.stats'))
            .then(res => {
                this.numBorrowers = res.data.num_borrowers
                this.numLentBooks = res.data.num_lent_books
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
