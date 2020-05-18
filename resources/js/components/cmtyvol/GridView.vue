<template>
    <div>

        <!-- Filter -->
        <div class="form-row">
            <div v-if="!!$slots['filter-prepend']" class="col-auto">
                <slot name="filter-prepend"></slot>
            </div>
            <div class="col">
                <table-filter
                    v-model="filterText"
                    :placeholder="filterPlaceholder"
                    :is-busy="isBusy"
                    :total-rows="totalRows"
                    @apply="applyFilter"
                    @clear="clearFilter"
                />
            </div>
            <div v-if="!!$slots['filter-append']" class="col-auto">
                <slot name="filter-append"></slot>
            </div>
        </div>

        <!-- <p>Filter Text: {{ filterText }}</p>
        <p>Filter: {{ filter }}</p> -->

        <!-- Loading or busy -->
        <div v-if="data == null || isBusy">
            {{ $t('app.loading') }}
        </div>

        <!-- Grid -->
        <div v-else-if="data.length > 0" class="row">
            <div
                v-for="item in data"
                :key="item.id"
                class="col-lg-2 col-md-3 col-sm-4 col-6"
            >
                <grid-item :item="item" />
            </div>
        </div>

        <!-- No items -->
        <b-alert v-else show variant="info">
            {{ $t('app.no_records_matching_your_request') }}
        </b-alert>

    </div>
</template>

<script>
import TableFilter from '@/components/table/TableFilter'
import GridItem from '@/components/cmtyvol/GridItem'
export default {
    components: {
        TableFilter,
        GridItem
    },
    props:  {
        apiMethod: {
            required: true,
            type: Function
        }
    },
    data () {
        return {
            data: null,
            busy: false,
            errorText: null,
            filter: '',
            filterText: '',
            currentPage: 1,
            perPage: 50,
            sortBy: 'first_name',
            sortDesc: false,
            totalRows: 0,
            filterPlaceholder: this.$t('app.search')
        }
    },
    watch: {
        filterText () {
            this.applyFilter()
        },
        filter () {
            this.refresh()
        }
    },
    async created () {
        this.refresh()
    },
    methods: {
        async itemProvider () {
            this.isBusy = true
            this.errorText = null
            const params = {
                filter: this.filter,
                page: this.currentPage,
                pageSize: this.perPage,
                sortBy: this.sortBy,
                sortDirection: this.sortDesc ? 'desc' : 'asc'
            }
            try {
                let data = await this.apiMethod(params)
                this.totalRows = data.meta.total
                this.isBusy = false
                return data.data || []
            } catch (err) {
                this.isBusy = false
                this.errorText = err
                return []
            }
        },
        async refresh () {
            this.data = await this.itemProvider()
        },
        applyFilter () {
            this.filter = this.filterText
            this.currentPage = 1
        },
        clearFilter () {
            this.filterText = ''
            this.filter = ''
            this.currentPage = 1
        }
    }
}
</script>