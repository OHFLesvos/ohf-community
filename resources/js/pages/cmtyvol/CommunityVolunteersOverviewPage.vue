<template>
    <b-container fluid>

        <!-- Table -->
        <base-table
            v-if="viewType == 'list'"
            ref="table"
            id="communityVolunteerTable"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="first_name"
            :empty-text="$t('No community volunteers found.')"
            :filter-placeholder="$t('Search')"
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
                <span class="pre-formatted">{{ arrayToString(data.value) }}</span>
            </template>
            <template v-slot:cell(responsibilities)="data">
                <template v-for="(attributes, name) in data.value" >
                    {{name}}
                    <b-button v-if="attributes.description" :key="name + '-a'" v-b-popover.focus="attributes.description" class="description-tooltip p-0" variant="link" href="#">
                        <font-awesome-icon :key="name + '-i'" icon="info-circle" />
                    </b-button>
                    <template v-if="attributes.start_date && attributes.end_date">
                        ({{ $t('{from} - {until}', { 'from': attributes.start_date, 'until': attributes.end_date }) }})
                    </template>
                    <template v-else-if="attributes.start_date">
                        ({{ $t('from {from}', { 'from': attributes.start_date }) }})
                    </template>
                    <template v-else-if="attributes.end_date">
                        ({{ $t('until {until}', { 'until': attributes.end_date }) }})
                    </template>
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

    </b-container>
</template>

<script>
import BaseTable from '@/components/table/BaseTable.vue'
import cmtyvolApi from '@/api/cmtyvol/cmtyvol'
import WorkStatusSelector from '@/components/cmtyvol/WorkStatusSelector.vue'
import ViewTypeSelector from '@/components/cmtyvol/ViewTypeSelector.vue'
import GridView from '@/components/cmtyvol/GridView.vue'
export default {
    title() {
        return this.$t("Community Volunteers");
    },
    components: {
        BaseTable,
        WorkStatusSelector,
        ViewTypeSelector,
        GridView
    },
    data() {
        return {
            fields: [
                {
                    key: 'first_name',
                    label: this.$t('First Name'),
                    sortable: true
                },
                {
                    key: 'family_name',
                    label: this.$t('Family Name'),
                    sortable: true
                },
                {
                    key: 'nickname',
                    label: this.$t('Nickname')
                },
                {
                    key: 'nationality',
                    label: this.$t('Nationality'),
                    sortable: true
                },
                {
                    key: 'gender',
                    label: this.$t('Gender'),
                    class: 'text-center fit'
                },
                {
                    key: 'age',
                    label: this.$t('Age'),
                    sortable: true,
                    class: 'text-right fit'
                },
                {
                    key: 'responsibilities',
                    label: this.$t('Responsibilities')
                },
                {
                    key: 'languages',
                    label: this.$t('Languages')
                },
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
