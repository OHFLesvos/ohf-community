<template>
    <base-table
        id="lendingLogTable"
        :fields="fields"
        :api-method="fetchPersonLog"
        default-sort-by="lending_date"
        default-sort-desc
        :empty-text="$t('library.no_books_lent_so_far')"
        no-filter
        :items-per-page="25"
    >
        <template v-slot:cell(book)="data">
            <a :href="route('library.lending.book', [data.item.book.id])">
                {{ data.item.book.title }}
            </a>
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
    props: {
        personId: {
            required: true,
        }
    },
    data () {
        return {
            fields: [
                {
                    key: 'book',
                    label: this.$t('library.book'),
                },
                {
                    key: 'lending_date',
                    label: this.$t('library.lent'),
                    formatter: (value) => moment(value).format('LL')
                },
                {
                    key: 'returned_date',
                    label: this.$t('library.returned'),
                    formatter: (value) => value ? moment(value).format('LL') : null
                },
            ]
        }
    },
    methods: {
        fetchPersonLog () {
            return libraryApi.fetchPersonLog(this.personId)
        }
    }
}
</script>
