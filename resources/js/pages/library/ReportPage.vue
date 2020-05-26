<template>
    <b-row v-if="loaded">
        <b-col md>

            <h2>
                {{ $t('library.borrowers') }}
                <small>{{ borrwer_count }}</small>
            </h2>

            <template v-if="borrwer_count > 0">

                <p>
                    <strong>{{ $t('library.currently_borrowing') }}:</strong>
                    {{ borrowers_currently_borrowed_count }}
                    <template v-if="borrowers_currently_borrowed_count > 0">
                        <strong>{{ $t('library.have_overdue_books') }}:</strong>
                        {{ borrowers_currently_overdue_count }}
                        ({{ roundWithDecimals(borrowers_currently_overdue_count / borrowers_currently_borrowed_count * 100, 1) }} %)
                    </template>
                </p>

                <!-- Popular -->
                <h3>{{ $t('app.regulars') }}</h3>
                <b-table-simple small bordered striped hover responsive>
                    <b-thead>
                        <b-tr>
                            <b-th>{{ $t('app.title') }}</b-th>
                            <b-th>{{ $t('people.age') }}</b-th>
                            <b-th>{{ $t('people.nationality') }}</b-th>
                            <b-th class="text-center">{{ $t('people.gender') }}</b-th>
                            <b-th class="text-right"># {{ $t('library.lendings') }}</b-th>
                        </b-tr>
                    </b-thead>
                    <b-tbody>
                        <b-tr v-for="(borrower, idx) in borrwer_lendings_top" :key="idx">
                            <b-td>
                                {{ borrower.collapsed_name }}
                            </b-td>
                            <b-td>{{ borrower.age }}</b-td>
                            <b-td>{{ borrower.nationality }}</b-td>
                            <b-td class="text-center">
                                    <gender-label
                                        :value="borrower.gender"
                                        icon-only
                                    />
                            </b-td>
                            <b-td class="text-right">
                                {{ borrower.quantity }}
                            </b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>

                <!-- Nationalities -->
                <h3>{{ $t('people.nationalities') }}</h3>
                <b-table-simple small bordered striped hover responsive>
                    <b-thead>
                        <b-tr>
                            <b-th>{{ $t('app.country') }}</b-th>
                            <b-th class="text-right">{{ $t('app.quantity') }}</b-th>
                            <b-th class="text-right">{{ $t('app.percentage') }}</b-th>
                        </b-tr>
                    </b-thead>
                    <b-tbody>
                        <b-tr v-for="nationality in borrwer_nationalities" :key="nationality.nationality">
                            <b-td>{{ nationality.nationality ? nationality.nationality : $t('app.unspecified') }}</b-td>
                            <b-td class="text-right">{{ nationality.quantity }}</b-td>
                            <b-td class="text-right">{{ roundWithDecimals(nationality.quantity / borrwer_count * 100, 1) }} %</b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>

                <!-- Genders -->
                <h3>{{ $t('people.gender') }}</h3>
                <b-table-simple small bordered striped hover responsive>
                    <b-thead>
                        <b-tr>
                            <b-th>{{ $t('people.gender') }}</b-th>
                            <b-th class="text-right">{{ $t('app.quantity') }}</b-th>
                            <b-th class="text-right">{{ $t('app.percentage') }}</b-th>
                        </b-tr>
                    </b-thead>
                    <b-tbody>
                        <b-tr v-for="gender in borrwer_genders" :key="gender.gender">
                            <b-td>
                                <gender-label
                                    :value="gender.gender"
                                    icon-only
                                />
                            </b-td>
                            <b-td class="text-right">{{ gender.quantity }}</b-td>
                            <b-td class="text-right">{{ roundWithDecimals(gender.quantity / borrwer_count * 100, 1) }} %</b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>

            </template>

        </b-col>
        <b-col md>

            <h2>
                {{ $t('library.books') }}
                <small>{{ book_count }}</small>
            </h2>

            <p>
                <strong>{{ $t('library.currently_borrowed') }}:</strong> {{ books_currently_borrowed_count }}
                <template v-if="books_currently_borrowed_count > 0">
                    <strong>{{ $t('library.overdue') }}:</strong>
                    {{ books_currently_overdue_count }}
                    ({{ roundWithDecimals(books_currently_overdue_count / books_currently_borrowed_count * 100, 1) }} %)
                </template>
                <br>
                <strong>{{ $t('library.books_lent') }}:</strong>
                {{ book_lendings_unique_count }} ({{ $t('library.percentage_of_all_books', {
                    percentage: roundWithDecimals(book_lendings_unique_count / book_count * 100, 1)
                }) }})<br>
                <strong>{{ $t('library.number_of_times_book_lent') }}:</strong>
                {{ book_lendings_all_count }}
            </p>

            <!-- Popular -->
            <template v-if="book_lendings_top.length > 0">
                <h3>{{ $t('app.popular') }}</h3>
                <b-table-simple small bordered striped hover responsive>
                    <b-thead>
                        <b-tr>
                            <b-th>{{ $t('app.title') }}</b-th>
                            <b-th>{{ $t('library.author') }}</b-th>
                            <b-th>{{ $t('app.language') }}</b-th>
                            <b-th class="text-right"># {{ $t('library.lendings') }}</b-th>
                        </b-tr>
                    </b-thead>
                    <b-tbody>
                        <b-tr v-for="(book, idx) in book_lendings_top" :key="idx">
                            <b-td v-html="truncate(book.title, 40)"></b-td>
                            <b-td v-html="truncate(book.author, 40)"></b-td>
                            <b-td>{{ book.language }}</b-td>
                            <b-td class="text-right">{{ book.quantity }}</b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
            </template>

            <!-- Languages -->
            <template v-if="book_count > 0">
                <h3>{{ $t('app.languages') }}</h3>
                <p v-if="book_languages_undefined_count > 0">
                    <em>{{ $t('library.books_without_language_specified', {
                        count: book_languages_undefined_count,
                        percentage: roundWithDecimals(book_languages_undefined_count / book_count * 100, 1)
                    }) }}</em>
                </p>
                <b-table-simple small bordered striped hover responsive>
                    <b-thead>
                        <b-tr>
                            <b-th>{{ $t('app.language') }}</b-th>
                            <b-th class="text-right">{{ $t('app.quantity') }}</b-th>
                            <b-th class="text-right">{{ $t('app.percentage') }}</b-th>
                        </b-tr>
                    </b-thead>
                    <b-tbody>
                        <b-tr v-for="language in book_languages_defined" :key="language.language">
                            <b-td>{{ language.language ? language.language : $t('app.unspecified') }}</b-td>
                            <b-td class="text-right">{{ language.quantity }}</b-td>
                            <b-td class="text-right">{{ roundWithDecimals(language.quantity / (book_count - book_languages_undefined_count) * 100, 1) }} %</b-td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>

            </template>

        </b-col>
    </b-row>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import libraryApi from '@/api/library'
import { roundWithDecimals } from '@/utils'
import GenderLabel from '@/components/people/GenderLabel'
export default {
    components: {
        GenderLabel
    },
    data () {
        return {
            loaded: false,
            borrwer_count: null,
            borrowers_currently_borrowed_count: null,
            borrowers_currently_overdue_count: null,
            borrwer_lendings_top: null,
            borrwer_nationalities: null,
            borrwer_genders: null,
            book_count: null,
            books_currently_borrowed_count: null,
            books_currently_overdue_count: null,
            book_lendings_unique_count: null,
            book_lendings_all_count: null,
            book_lendings_top: null,
            book_languages: null
        }
    },
    computed: {
        book_languages_defined () {
            return this.book_languages.filter(l => l.language_code != null)
        },
        book_languages_undefined_count () {
            return this.book_languages.filter(l => l.language_code == null).reduce((a, b) => a + b.quantity, 0)
        }
    },
    created () {
        this.loadData()
    },
    methods: {
        async loadData () {
            try {
                let data = await libraryApi.fetchReportData()
                this.borrwer_count = data.borrwer_count
                this.borrowers_currently_borrowed_count = data.borrowers_currently_borrowed_count
                this.borrowers_currently_overdue_count = data.borrowers_currently_overdue_count
                this.borrwer_lendings_top = data.borrwer_lendings_top
                this.borrwer_nationalities = data.borrwer_nationalities
                this.borrwer_genders = data.borrwer_genders
                this.book_count = data.book_count
                this.books_currently_borrowed_count = data.books_currently_borrowed_count
                this.books_currently_overdue_count = data.books_currently_overdue_count
                this.book_lendings_unique_count = data.book_lendings_unique_count
                this.book_lendings_all_count = data.book_lendings_all_count
                this.book_lendings_top = data.book_lendings_top
                this.book_languages = data.book_languages
                this.loaded = true
            } catch (err) {
                alert(err)
            }
        },
        roundWithDecimals,
        truncate (str, n) {
            if (str == null) return null
            return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
        }
    }
}
</script>
