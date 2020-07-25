<template>
    <div>

        <!-- Table -->
        <base-table
            v-if="viewType == 'list'"
            ref="table"
            id="communityVolunteerTable"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="first_name"
            :empty-text="$t('cmtyvol.none_found')"
            :filter-placeholder="$t('app.search')"
            :items-per-page="itemsPerPage"
        >
            <template v-slot:cell(first_name)="data">
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
                <template v-for="(description, name) in data.value" >
                    {{name}}
                    <b-button :key="name + '-a'" v-b-popover.focus="description" class="description-tooltip p-0" variant="link" href="#">
                        <font-awesome-icon :key="name + '-i'" icon="info-circle" />
                    </b-button>
                    <br :key="name + '-b'" />
                </template>
            </template>

            <template v-slot:filter-prepend>
                <work-status-selector v-model="workStatus" />
            </template>
            <template v-slot:filter-append>
                <view-type-selector v-model="viewType" />
            </template>
        </base-table>

        <!-- Grid -->
        <grid-view
            v-else-if="viewType == 'grid'"
            ref="grid"
            :api-method="fetchData"
            :items-per-page="itemsPerPage"
        >
            <template v-slot:filter-prepend>
                <work-status-selector v-model="workStatus" />
            </template>
            <template v-slot:filter-append>
                <view-type-selector v-model="viewType" />
            </template>
        </grid-view>

    </div>
</template>

<script>
// import moment from 'moment'
import Nl2br from 'vue-nl2br'
import BaseTable from '@/components/table/BaseTable'
import cmtyvolApi from '@/api/cmtyvol/cmtyvol'
import WorkStatusSelector from '@/components/cmtyvol/WorkStatusSelector'
import ViewTypeSelector from '@/components/cmtyvol/ViewTypeSelector'
import GridView from '@/components/cmtyvol/GridView'
export default {
    components: {
        BaseTable,
        Nl2br,
        WorkStatusSelector,
        ViewTypeSelector,
        GridView
    },
    data() {
        return {
            fields: [
                {
                    key: 'first_name',
                    label: this.$t('people.first_name'),
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
                    class: 'text-center fit'
                },
                {
                    key: 'age',
                    label: this.$t('people.age'),
                    sortable: true,
                    class: 'text-right fit'
                },
                {
                    key: 'responsibilities',
                    label: this.$t('app.responsibilities')
                },
                {
                    key: 'languages',
                    label: this.$t('people.languages')
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
            ],
            workStatus: sessionStorage.getItem('cmtyvol.workStatus')
                ? sessionStorage.getItem('cmtyvol.workStatus')
                : 'active',
            viewType: sessionStorage.getItem('cmtyvol.viewType')
                ? sessionStorage.getItem('cmtyvol.viewType')
                : 'list',
            itemsPerPage: 12
        }
    },
    watch: {
        workStatus (val) {
            let table = this.$refs.table
            if (table) {
                table.refresh()
            }
            let grid = this.$refs.grid
            if (grid) {
                grid.refresh()
            }

            sessionStorage.setItem('cmtyvol.workStatus', val)
        },
        viewType (val) {
            sessionStorage.setItem('cmtyvol.viewType', val)
        }
    },
    methods: {
        fetchData (params) {
            params.workStatus = this.workStatus
            return cmtyvolApi.list(params)
        },
        arrayToString (value) {
            if (Array.isArray(value)) {
                return value.join('\n')
            }
            return value
        }
    }
}
</script>
