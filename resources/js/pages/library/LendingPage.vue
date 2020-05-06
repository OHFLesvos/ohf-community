<template>
    <div class="row">
        <div class="col-md">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    {{ ucFirst($t('people.persons')) }}
                    <a
                        v-if="num_borrowers !== null"
                        :href="route('library.lending.persons')"
                    >
                        {{ $t('library.borrowers') }} ({{ num_borrowers }})
                    </a>
                </div>
                <div class="card-body pb-4">
                    <div class="form-row">
                        <div class="col">
                            <person-autocomplete-input @select="navigateToPerson" />
                        </div>
                        <div class="col-auto">
                            <b-button
                                id="button-register-person"
                                variant="outline-secondary"
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
                        v-if="num_lent_books !== null"
                        :href="route('library.lending.books')"
                    >
                        {{ $t('library.lent_books') }} ({{ num_lent_books }})
                    </a>
                </div>
                <div class="card-body pb-4">
                    <library-book-autocomplete-input @select="navigateToBook" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ucFirst } from '@/utils'
import axios from '@/plugins/axios'
import PersonAutocompleteInput from '@/components/people/PersonAutocompleteInput'
import LibraryBookAutocompleteInput from '@/components/library/LibraryBookAutocompleteInput'
export default {
    components: {
        PersonAutocompleteInput,
        LibraryBookAutocompleteInput
    },
    data () {
        return {
            num_borrowers: null,
            num_lent_books: null
        }
    },
    created () {
        axios.get(this.route('api.library.lending.stats'))
            .then(res => {
                this.num_borrowers = res.data.num_borrowers
                this.num_lent_books = res.data.num_lent_books
            })
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
        }
    }
}
</script>
