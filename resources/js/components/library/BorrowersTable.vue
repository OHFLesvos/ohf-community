<template>
    <base-table
        id="borrowersTable"
        :fields="fields"
        :api-method="listBorrowers"
        default-sort-by="person"
        :empty-text="$t('library.no_books_lent')"
        no-filter
        :items-per-page="25"
    >
        <template v-slot:cell(person)="data">
            <b-link :to="{ name: 'library.lending.person', params: { personId: data.item.public_id }}">
                {{ data.item.full_name }}
            </b-link>
        </template>
    </base-table>
</template>

<script>
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
                    key: 'person',
                    label: this.$t('people.person'),
                },
                {
                    key: 'lendings_count',
                    label: this.$t('library.books'),
                    class: 'fit text-right',
                    tdClass: (value, key, item) => item.lendings_overdue ? 'table-danger' : null
                }
            ]
        }
    },
    methods: {
        listBorrowers: libraryApi.listBorrowers
    }
}
</script>
