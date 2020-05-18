<template>
    <div>

        <!-- Error -->
        <table-alert
            :value="errorText"
            @retry="refresh"
        />

        <!-- Tags -->
        <p v-if="Object.keys(tags).length > 0" class="mb-3">
            {{ $t('app.tags') }}:
            <tag-select-button
                :label="tag_name"
                :value="tag_key"
                :toggled="tagSelected(tag_key)"
                @toggled="toggleTag"
                v-for="(tag_name, tag_key) in tags" :key="tag_key"
            ></tag-select-button>
        </p>

        <!-- Filter  -->
        <b-form-row>
            <b-col v-if="!!$slots['filter-prepend']" cols="auto">
                <slot name="filter-prepend"></slot>
            </b-col>
            <b-col>
                <table-filter
                    v-if="!noFilter"
                    v-model="filterText"
                    :placeholder="filterPlaceholder"
                    :is-busy="isBusy"
                    :total-rows="totalRows"
                    @apply="applyFilter"
                    @clear="clearFilter"
                />
            </b-col>
            <b-col v-if="!!$slots['filter-append']" cols="auto">
                <slot name="filter-append"></slot>
            </b-col>
        </b-form-row>

        <!-- Table -->
        <b-table
            :id="id"
            striped
            hover
            small
            bordered
            responsive
            :items="itemProvider"
            :fields="fields"
            :primary-key="'id'"
            :busy.sync="isBusy"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :per-page="perPage"
            :current-page="currentPage"
            :show-empty="true"
            :empty-text="emptyText"
            :empty-filtered-text="$t('app.no_records_matching_your_request')"
            :no-sort-reset="true"
            :filter="filter"
        >
            <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope"><slot :name="slot" v-bind="scope"/></template>
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ loadingLabel }}</strong>
            </div>
            <template slot="empty" slot-scope="scope">
                <em>{{ scope.emptyText }}</em>
            </template>
            <template slot="emptyfiltered" slot-scope="scope">
                <em>{{ scope.emptyFilteredText }}</em>
            </template>
        </b-table>

        <!-- Pagination -->
        <table-pagination
            v-model="currentPage"
            :total-rows="totalRows"
            :per-page="perPage"
        />

    </div>
</template>

<script>
import TagSelectButton from '@/components/tags/TagSelectButton'
import TableAlert from '@/components/table/TableAlert'
import TableFilter from '@/components/table/TableFilter'
import TablePagination from '@/components/table/TablePagination'
export default {
    components: {
        TagSelectButton,
        TableAlert,
        TableFilter,
        TablePagination
    },
    props: {
        id: {
            required: true,
            type: String
        },
        fields: {
            required: false,
            type: Array
        },
        apiMethod: {
            required: true,
            type: Function
        },
        defaultSortBy: {
            required: true,
            type: String
        },
        defaultSortDesc: {
            required: false,
            type: Boolean,
            default: false
        },
        emptyText: {
            required: false,
            type: String
        },
        itemsPerPage: {
            required: false,
            type: Number,
            default: 25
        },
        filterPlaceholder: {
            require: false,
            type: String,
            default: function() {
                return this.$t('app.type_to_search')
            }
        },
        tags: {
            require: false,
            type: Object,
            default: () => {
                return {}
            }
        },
        tag: {
            require: false,
            type: String,
            default: null
        },
        loadingLabel: {
            type: String,
            required: false,
            default: function() {
                return this.$t('app.loading')
            }
        },
        noFilter: Boolean
    },
    data() {
        return {
            isBusy: false,
            sortBy: sessionStorage.getItem(this.id + '.sortBy')
                ? sessionStorage.getItem(this.id + '.sortBy')
                : this.defaultSortBy,
            sortDesc: sessionStorage.getItem(this.id + '.sortDesc')
                ? sessionStorage.getItem(this.id + '.sortDesc') == 'true'
                : this.defaultSortDesc,
            currentPage: sessionStorage.getItem(this.id + '.currentPage')
                ? parseInt(sessionStorage.getItem(this.id + '.currentPage'))
                : 1,
            perPage: this.itemsPerPage,
            totalRows: 0,
            errorText: null,
            filter: sessionStorage.getItem(this.id + '.filter')
                ? sessionStorage.getItem(this.id + '.filter')
                : '',
            filterText: sessionStorage.getItem(this.id + '.filter')
                ? sessionStorage.getItem(this.id + '.filter')
                : '',
            selectedTags: this.getSelectedTags()
        }
    },
    methods: {
        getSelectedTags () {
            let tags
            if (this.tag) {
                tags = [this.tag]
                sessionStorage.setItem(this.id + '.selectedTags', JSON.stringify(tags))
            } else if (sessionStorage.getItem(this.id + '.selectedTags')) {
                tags = JSON.parse(sessionStorage.getItem(this.id + '.selectedTags'))
            } else {
                tags = []
            }
            return tags.filter(e => Object.keys(this.tags).indexOf(e) >= 0)
        },
        applyFilter () {
            this.filter = this.filterText
            this.currentPage = 1
        },
        clearFilter () {
            this.filterText = ''
            this.filter = ''
            this.currentPage = 1
        },
        async itemProvider (ctx) {
            this.isBusy = true
            this.errorText = null
            const params = {
                filter: ctx.filter,
                page: ctx.currentPage,
                pageSize: ctx.perPage,
                sortBy: ctx.sortBy,
                sortDirection: ctx.sortDesc ? 'desc' : 'asc',
                tags: []
            }
            for (let i = 0; i < this.selectedTags.length; i++) {
                params.tags.push(this.selectedTags[i])
            }
            try {
                let data = await this.apiMethod(params)
                this.isBusy = false
                this.totalRows = data.meta.total
                sessionStorage.setItem(this.id + '.sortBy', ctx.sortBy)
                sessionStorage.setItem(this.id + '.sortDesc', ctx.sortDesc)
                sessionStorage.setItem(this.id + '.currentPage', ctx.currentPage)
                return data.data || []
            } catch (err) {
                this.errorText = err
                this.totalRows = 0
                return []
            }
        },
        toggleTag (value, toggled) {
            this.selectedTags = this.selectedTags.filter((v) => v != value)
            if (toggled) {
                this.selectedTags.push(value)
            }
            sessionStorage.setItem(this.id + '.selectedTags', JSON.stringify(this.selectedTags))
            this.refresh()
        },
        tagSelected (key) {
            return this.selectedTags.indexOf(key) >= 0
        },
        refresh () {
            this.$root.$emit('bv::refresh::table', this.id)
        }
    },
    watch: {
        filter (val) {
            sessionStorage.setItem(this.id + '.filter', val)
        },
        filterText () {
            this.applyFilter()
        }
    }
}
</script>
