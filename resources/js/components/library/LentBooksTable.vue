<template>
    <base-table
        id="lentBooksTable"
        :fields="fields"
        :api-url="route('api.library.lending.books')"
        default-sort-by="book"
        :empty-text="$t('library.no_books_lent')"
        no-filter
        :items-per-page="25"
    >
        <template v-slot:cell(book)="data">
            <a :href="route('library.lending.book', [data.item.id])">
                {{ data.item.title }}<template v-if="data.item.author">, {{ data.item.author }}</template>
            </a>
        </template>
        <template v-slot:cell(person)="data">
            <a
                v-if="data.item.lending.person"
                :href="route('library.lending.person', [data.item.lending.person.public_id])"
            >
                {{ data.item.lending.person.full_name }}
            </a>
        </template>
    </base-table>
</template>

<script>
import moment from 'moment'
import BaseTable from '@/components/BaseTable'
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
    }
}
</script>
