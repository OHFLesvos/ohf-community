<template>
    <div class="mt-3">
        <base-table
            id="lentBooksTable"
            :fields="fields"
            :api-method="listLentBooks"
            default-sort-by="book"
            :empty-text="$t('library.no_books_lent')"
            no-filter
            :items-per-page="25"
        >
            <template v-slot:cell(book)="data">
                <b-link :to="{ name: 'library.lending.book', params: { bookId: data.item.id }}">
                    {{ data.item.title }}<template v-if="data.item.author">, {{ data.item.author }}</template>
                </b-link>
            </template>
            <template v-slot:cell(person)="data">
                <b-link
                    v-if="data.item.lending.person"
                    :to="{ name: 'library.lending.person', params: { personId: data.item.lending.person.public_id }}"
                >
                    {{ data.item.lending.person.full_name }}
                </b-link>
            </template>
        </base-table>
    </div>
</template>

<script>
import moment from 'moment'
import BaseTable from '@/components/table/BaseTable'
import libraryApi from '@/api/library'
export default {
    components: {
        BaseTable
    },
    data () {
        return {
            fields: [
                {
                    key: 'book',
                    label: this.$t('library.book'),
                },
                {
                    key: 'person',
                    label: this.$t('people.person'),
                    class: 'd-none d-sm-table-cell'
                },
                {
                    key: 'return_date',
                    label: this.$t('library.return'),
                    class: 'fit',
                    formatter: (value, key, item) => moment(item.lending.return_date).format('LL'),
                    tdClass: (value, key, item) => {
                        if (moment(item.lending.return_date).isBefore(moment(), 'day')) {
                            return 'table-danger'
                        }
                        if (moment(item.lending.return_date).isSame(moment(), 'day')) {
                            return 'table-warning'
                        }
                        return null
                    }
                }
            ]
        }
    },
    methods: {
        listLentBooks: libraryApi.listLentBooks
    }
}
</script>
