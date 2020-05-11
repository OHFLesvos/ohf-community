<template>
    <base-table
        id="lendingLogTable"
        :fields="fields"
        :api-method="fetchBookLog"
        default-sort-by="lending_date"
        default-sort-desc
        :empty-text="$t('library.not_lent_to_anyone_so_far')"
        no-filter
        :items-per-page="25"
    >
        <template v-slot:cell(person)="data">
            <a :href="route('library.lending.person', [data.item.person.public_id])">
                {{ data.item.person.full_name }}
            </a>
        </template>
    </base-table>
</template>

<script>
import moment from 'moment'
import BaseTable from '@/components/BaseTable'
import libraryApi from '@/api/library'
export default {
    components: {
        BaseTable
    },
    props: {
        bookId: {
            required: true,
        }
    },
    data () {
        return {
            fields: [
                {
                    key: 'person',
                    label: this.$t('people.person'),
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
        fetchBookLog () {
            return libraryApi.fetchBookLog(this.bookId)
        }
    }
}
</script>
