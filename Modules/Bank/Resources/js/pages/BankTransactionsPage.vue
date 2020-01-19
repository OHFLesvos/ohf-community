<template>
    <div>
        <!-- Filter input field -->
        <b-form-input
            v-model="filter"
            type="search"
            debounce="500"
            :placeholder="lang['app.filter']"
            class="mb-3"
            size="sm"
        ></b-form-input>

        <!-- Table of audit records -->
        <b-table
            ref="table"
            striped small hover responsive bordered
            primary-key="id"
            :busy.sync="isBusy"
            :items="itemProvider"
            :fields="fields"
            :current-page="currentPage"
            :per-page="perPage"
            :api-url="apiUrl"
            :show-empty="initialized"
            :empty-text="lang['people::people.no_transactions_so_far']"
            :empty-filtered-text="lang['app.no_records_matching_your_request']"
            :filter="filter"
        >
            <!-- Busy state -->
            <template
                v-if="!initialized"
                v-slot:table-busy
            >
                <div class="text-center my-2">
                    <font-awesome-icon
                        icon="spinner"
                        spin
                    />
                    {{ lang['app.loading'] }}
                </div>
            </template>

            <!-- Created at column -->
            <template v-slot:cell(created_at)="data">
                <span :title="data.value">
                    {{ data.item.created_at_diff }}
                </span>
                <small
                    v-if="data.item.user"
                    class="text-muted"
                >
                    <font-awesome-icon icon="user"/>
                    {{ data.item.user }}
                </small>
            </template>

            <!-- Date column -->
            <template v-slot:cell(date)="data">
                <template v-if="data.value">
                    {{ data.value }}
                </template>
                <em v-else>
                    {{ lang['app.not_found'] }}
                </em>
            </template>

            <!-- Recipient column -->
            <template v-slot:cell(person)="data">
                <person-label
                    v-if="data.value"
                    :person="data.value"
                />
                <em v-else>
                    {{ lang['app.not_found'] }}
                </em>
            </template>

            <!-- Amount column -->
            <template v-slot:cell(coupon)="data">
                <template v-if="data.value">
                    {{ data.value.amount_diff }}
                    {{ data.value.name }}
                </template>
                <em v-else>
                    {{ lang['app.not_found'] }}
                </em>
            </template>
        </b-table>

        <!-- Footer -->
        <b-row>
            <b-col>

                <!-- Pagination -->
                <b-pagination
                    v-if="totalRows > 0 && perPage > 0"
                    v-model="currentPage"
                    size="sm"
                    :total-rows="totalRows"
                    :per-page="perPage"
                />

            </b-col>
            <b-col cols="auto">

                <!-- Refresh button -->
                <b-button
                    v-if="initialized"
                    variant="primary"
                    size="sm"
                    :disabled="isBusy"
                    @click="refresh"
                >
                    <font-awesome-icon
                        icon="sync"
                        :spin="isBusy"
                    />
                </b-button>

            </b-col>
        </b-row>
    </div>
</template>

<script>
const ITEMS_PER_PAGE = 100
import { BTable, BPagination, BButton, BRow, BCol, BFormInput } from 'bootstrap-vue'
import PersonLabel from '../components/PersonLabel'
import { handleAjaxError } from '@app/utils'
export default {
    components: {
        BTable,
        BPagination,
        BButton,
        BRow,
        BCol,
        BFormInput,
        PersonLabel
    },
    props: {
        apiUrl: {
            type: String,
            required: true
        },
        lang: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            fields: [
                {
                    key: 'created_at',
                    label: this.lang['app.registered']
                },
                {
                    key: 'date',
                    label: this.lang['app.date']
                },
                {
                    key: 'person',
                    label: this.lang['people::people.recipient']
                },
                {
                    key: 'coupon',
                    label: this.lang['app.amount']
                }
            ],
            totalRows: 0,
            perPage: ITEMS_PER_PAGE,
            currentPage: 1,
            isBusy: false,
            initialized: false,
            filter: ''
        }
    },
    methods: {
        refresh() {
            this.$refs.table.refresh()
        },
        itemProvider(ctx) {
            this.isBusy = true
            return axios.get(`${ctx.apiUrl}?page=${ctx.currentPage}&perPage=${ctx.perPage}&filter=${ctx.filter}`)
                .then(res => {
                    const items = res.data.data
                    this.totalRows = res.data.meta && res.data.meta ? res.data.meta.total : 0
                    this.isBusy = false
                    this.initialized = true
                    return items || []
                })
                .catch(err => {
                    handleAjaxError(err)
                    this.isBusy = false
                    return []
                })
        }
    }
}
</script>