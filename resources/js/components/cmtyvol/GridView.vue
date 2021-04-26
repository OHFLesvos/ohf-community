<template>
    <div>

        <!-- Error -->
        <alert-with-retry
            :value="errorText"
            @retry="refresh"
        />

        <!-- Filter -->
        <div class="form-row">
            <div v-if="!!$slots['filter-prepend']" class="col-auto">
                <slot name="filter-prepend"></slot>
            </div>
            <div class="col">
                <table-filter
                    v-model="filter"
                    :placeholder="filterPlaceholder"
                    :is-busy="isBusy"
                    :total-rows="totalRows"
                />
            </div>
            <div v-if="!!$slots['filter-append']" class="col-auto">
                <slot name="filter-append"></slot>
            </div>
        </div>

        <!-- Loading or busy -->
        <p v-if="data == null || isBusy">
            {{ $t('Loading...') }}
        </p>

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
            {{ $t('There are no records matching your request.') }}
        </b-alert>

        <!-- Pagination -->
        <table-pagination
            v-model="currentPage"
            :total-rows="totalRows"
            :per-page="perPage"
            :disabled="isBusy"
        />

    </div>
</template>

<script>
import AlertWithRetry from '@/components/alerts/AlertWithRetry'
import TableFilter from '@/components/table/TableFilter'
import TablePagination from '@/components/table/TablePagination'
import GridItem from '@/components/cmtyvol/GridItem'
export default {
    components: {
        AlertWithRetry,
        TableFilter,
        TablePagination,
        GridItem
    },
    props:  {
        apiMethod: {
            required: true,
            type: Function
        },
        itemsPerPage: {
            required: false,
            type: Number,
            default: 25
        }
    },
    data () {
        const id = 'communityVolunteerGrid'
        return {
            id: id,
            data: null,
            isBusy: false,
            errorText: null,
            currentPage: sessionStorage.getItem(id + '.currentPage')
                ? parseInt(sessionStorage.getItem(id + '.currentPage'))
                : 1,
            perPage: this.itemsPerPage,
            sortBy: 'first_name',
            sortDesc: false,
            totalRows: 0,
            filter: '',
            filterPlaceholder: this.$t('Search')
        }
    },
    watch: {
        filter () {
            this.currentPage = 1
            this.refresh()
        },
        currentPage () {
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
                sessionStorage.setItem(this.id + '.currentPage', this.currentPage)
                return data.data || []
            } catch (err) {
                this.isBusy = false
                this.errorText = err
                return []
            }
        },
        async refresh () {
            this.data = await this.itemProvider()
        }
    }
}
</script>
