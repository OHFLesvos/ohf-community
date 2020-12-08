<template>
    <div>
        <b-row>
            <b-col md>
                <b-card
                    :header="ucFirst($t('people.persons'))"
                    class="mb-4 shadow-sm"
                    body-class="pb-4"
                >
                    <b-form-row>
                        <b-col>
                            <person-autocomplete-input @select="navigateToPerson" />
                        </b-col>
                        <b-col cols="auto">
                            <b-button
                                variant="outline-secondary"
                                @click="registerPerson()"
                            >
                                <font-awesome-icon icon="plus-circle" />
                                <span class="d-none d-sm-inline">{{ $t('app.register') }}</span>
                            </b-button>
                        </b-col>
                    </b-form-row>
                </b-card>
            </b-col>
            <b-col md>
                <b-card
                    :header="$t('library.books')"
                    class="mb-4 shadow-sm"
                    body-class="pb-4"
                >
                    <library-book-autocomplete-input @select="navigateToBook" />
                </b-card>
            </b-col>
        </b-row>
        <person-register-modal
            ref="registerPersonModal"
        />
    </div>
</template>

<script>
import { ucFirst } from '@/utils'
import PersonAutocompleteInput from '@/components/people/PersonAutocompleteInput'
import LibraryBookAutocompleteInput from '@/components/library/input/LibraryBookAutocompleteInput'
import PersonRegisterModal from '@/components/people/PersonRegisterModal'
export default {
    components: {
        PersonAutocompleteInput,
        LibraryBookAutocompleteInput,
        PersonRegisterModal,
    },
    methods: {
        ucFirst,
        navigateToPerson (val) {
            if (val) {
                this.$router.push({ name: 'library.lending.person', params: { personId: val }})
            }
        },
        navigateToBook (val) {
            if (val) {
                this.$router.push({ name: 'library.lending.book', params: { bookId: val }})
            }
        },
        registerPerson () {
            this.$refs.registerPersonModal.open()
        }
    }
}
</script>
