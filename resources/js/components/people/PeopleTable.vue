<template>
    <base-table
        id="peopleTable"
        :fields='fields'
        :api-url="apiUrl"
        default-sort-by="name"
        :empty-text="$t('people.no_persons_found')"
        :filter-placeholder="$t('people.bank_search_text')"
        :items-per-page="15"
    >
        <template v-slot:cell(name)="data">
            <template v-if="data.item.url != null">
                <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
            </template>
            <template v-else>
                {{ data.value }}
            </template>
        </template>
        <template v-slot:cell(family_name)="data">
            <template v-if="data.item.url != null">
                <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
            </template>
            <template v-else>
                {{ data.value }}
            </template>
        </template>
    </base-table>
</template>

<script>
import BaseTable from '@/components/BaseTable'
export default {
    components: {
        BaseTable
    },
    props: {
        apiUrl: {
            required: true,
            type: String
        },
    },
    data() {
        return {
            fields: [
                {
                    key: 'name',
                    label: this.$t('app.name'),
                    sortable: true
                },
                {
                    key: 'family_name',
                    label: this.$t('people.family_name'),
                    sortable: true
                },
                {
                    key: 'date_of_birth',
                    label: this.$t('people.date_of_birth'),
                    sortable: true
                },
                {
                    key: 'nationality',
                    label: this.$t('people.nationality'),
                    sortable: true
                },
                {
                    key: 'police_no_formatted',
                    label: this.$t('people.police_no'),
                    sortable: false
                },
                {
                    key: 'languages',
                    label: this.$t('people.languages'),
                    sortable: true
                },
                {
                    key: 'remarks',
                    label: this.$t('people.remarks'),
                    sortable: true
                }
            ]
        }
    }
}
</script>
