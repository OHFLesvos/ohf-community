<template>
    <base-table
        id="booksTable"
        :fields="fields"
        :api-method="listBooks"
        default-sort-by="title"
        :empty-text="$t('library.no_books_registered')"
        :filter-placeholder="$t('app.search_ellipsis')"
        :items-per-page="100"
    >
        <template v-slot:cell(title)="data">
            <b-link :to="{ name: 'library.lending.book', params: { bookId: data.item.id }}">
                {{ data.value }}
            </b-link>
        </template>
    </base-table>
</template>

<script>
import moment from 'moment'
import BaseTable from '@/components/table/BaseTable'
import libraryApi from '@/api/library'
export default {
    components: {
        BaseTable
    },
    data() {
        return {
            fields: [
                {
                    key: 'title',
                    label: this.$t('app.title'),
                    sortable: true
                },
                {
                    key: 'author',
                    label: this.$t('library.author'),
                    sortable: true
                },
                {
                    key: 'isbn',
                    label: this.$t('library.isbn'),
                    class: 'd-none d-md-table-cell',
                    sortable: false
                },
                {
                    key: 'language',
                    label: this.$t('app.language'),
                    class: 'd-none d-sm-table-cell',
                    sortable: false
                },
                {
                    key: 'created_at',
                    label: this.$t('app.registered'),
                    class: 'd-none d-sm-table-cell fit',
                    sortable: true,
                    sortDirection: 'desc',
                    formatter: value => {
                        return moment(value).fromNow()
                    }
                }
            ]
        }
    },
    methods: {
        listBooks: libraryApi.listBooks
    }
}
</script>
