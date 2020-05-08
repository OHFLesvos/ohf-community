<template>
    <base-table
        id="borrowersTable"
        :fields="fields"
        :api-url="route('api.library.lending.persons')"
        default-sort-by="person"
        :empty-text="$t('library.no_books_lent')"
        no-filter
        :items-per-page="25"
    >
        <template v-slot:cell(person)="data">
            <a :href="route('library.lending.person', [data.item.public_id])">
                {{ data.item.full_name }}
            </a>
        </template>
    </base-table>
</template>

<script>
import BaseTable from '@/components/BaseTable'
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
    }
}
</script>
