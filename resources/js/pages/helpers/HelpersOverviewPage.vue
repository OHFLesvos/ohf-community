<template>
    <div>
        <base-table
            id="helperTable"
            :fields='fields'
            :api-url="route('api.people.helpers.index')"
            default-sort-by="name"
            :empty-text="$t('people.no_helpers_found')"
            :filter-placeholder="$t('app.search')"
            :items-per-page="50"
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
            <template v-slot:cell(gender)="data">
                <font-awesome-icon v-if="data.value=='m'" icon="male" />
                <font-awesome-icon v-if="data.value=='f'" icon="female" />
            </template>
            <template v-slot:cell(languages)="data">
                <nl2br tag="span" :text="arrayToString(data.value)" />
            </template>
            <template v-slot:cell(responsibilities)="data">
                <nl2br tag="span" :text="arrayToString(data.value)" />
            </template>
        </base-table>
    </div>
</template>

<script>
// import moment from 'moment'
import Nl2br from 'vue-nl2br'
import BaseTable from '@/components/BaseTable'
export default {
    components: {
        BaseTable,
        Nl2br
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
                    key: 'nickname',
                    label: this.$t('people.nickname')
                },
                {
                    key: 'nationality',
                    label: this.$t('people.nationality'),
                    sortable: true
                },
                {
                    key: 'gender',
                    label: this.$t('people.gender'),
                    class: 'text-center'
                },
                {
                    key: 'age',
                    label: this.$t('people.age'),
                    sortable: true,
                    class: 'text-right'
                },
                {
                    key: 'languages',
                    label: this.$t('people.languages')
                },
                {
                    key: 'responsibilities',
                    label: this.$t('app.responsibilities')
                },
                // {
                //     key: 'created_at',
                //     label: this.$t('app.registered'),
                //     class: 'd-none d-sm-table-cell fit',
                //     sortable: true,
                //     sortDirection: 'desc',
                //     formatter: value => {
                //         return moment(value).fromNow()
                //     }
                // }
            ]
        }
    },
    methods: {
        arrayToString (value) {
            if (Array.isArray(value)) {
                return value.join('\n')
            }
            return value
        }
    }
}
</script>
