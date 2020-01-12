<template>
    <div>
        <b-form-input
            type="search"
            v-model="filter"
            debounce="500"
            :placeholder="lang['app.filter']"
            class="mb-3"
            size="sm"
        ></b-form-input>
        <b-table
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
            ref="table"
            :filter="filter"
        >
            <template v-slot:table-busy v-if="!initialized">
                <div class="text-center my-2">
                    <icon name="spinner" :spin="true"></icon> {{ lang['app.loading'] }}
                </div>
            </template>
            <template v-slot:cell(created_at)="data">
                <span :title="data.value">{{ data.item.created_at_diff }}</span>
                <small class="text-muted" v-if="data.item.user">
                    <icon name="user"></icon> {{ data.item.user }}
                </small>
            </template>
            <template v-slot:cell(date)="data">
                <template v-if="data.value">{{ data.value }}</template>
                <em v-else>{{ lang['app.not_found'] }}</em>
            </template>
            <template v-slot:cell(person)="data">
                <template v-if="data.value">
                    <icon name="female" v-if="data.value.gender == 'f'"></icon>
                    <icon name="male" v-if="data.value.gender == 'm'"></icon>
                    <a :href="data.value.url" v-if="data.value.url" target="_blank">{{ data.value.name }}</a>
                    <template v-else>{{ data.value.name }}</template>
                    <template v-if="data.value.date_of_birth">
                        {{ data.value.date_of_birth }} ({{ data.value.age }})
                    </template>
                    <template v-if="data.value.nationality">
                        <icon name="globe"></icon>
                        {{ data.value.nationality }}
                    </template>
                </template>
                <em v-else>{{ lang['app.not_found'] }}</em>
            </template>
            <template v-slot:cell(coupon)="data">
                <template v-if="data.value">
                    {{ data.value.amount_diff }}
                    {{ data.value.name }}
                </template>
                <em v-else>{{ lang['app.not_found'] }}</em>
            </template>
        </b-table>
        <b-row>
            <b-col>
                <b-pagination
                    v-if="totalRows > 0 && perPage > 0"
                    size="sm"
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                ></b-pagination>
            </b-col>
            <b-col cols="auto">
                <b-button
                    v-if="initialized"
                    variant="primary"
                    size="sm"
                    @click="refresh"
                    :disabled="isBusy"
                >
                    <icon name="sync" :spin="isBusy"></icon>
                </b-button>
            </b-col>
        </b-row>
    </div>
</template>

<script>
const ITEMS_PER_PAGE = 100
import { BTable, BPagination, BButton, BRow, BCol, BFormInput } from 'bootstrap-vue'
import { handleAjaxError } from '@app/utils'
export default {
    components: {
        BTable,
        BPagination,
        BButton,
        BRow,
        BCol,
        BFormInput
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